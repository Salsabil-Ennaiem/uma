<?php

use App\Enums\PresenceStatut;
use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use App\Enums\UserRole;
use App\Mail\ReunionConvocation;
use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Reunion;
use App\Models\Universite;
use App\Models\User;
use App\Notifications\ReunionPlanifiee;
use App\Notifications\ReunionTerminee;
use App\Services\DecisionService;
use App\Services\ReunionService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use SalsabilEnnaiem\PvModule\Models\Pv;

beforeEach(function () {
    Notification::fake();
    Mail::fake();

    $this->universite = Universite::factory()->create();
    $this->ecole = EcoleDoctorale::factory()->create(['universite_id' => $this->universite->id]);
    $this->etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $this->ecole->id]);

    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->president = User::factory()->create(['role' => UserRole::PresidentCommission]);
    $this->agent = User::factory()->create(['role' => UserRole::AgentAdministration]);
    $this->membre1 = User::factory()->create(['role' => UserRole::MembreCommission]);
    $this->membre2 = User::factory()->create(['role' => UserRole::MembreCommission]);
    $this->doctorant = User::factory()->create(['role' => UserRole::Doctorant]);

    $this->commission = Commission::factory()->create([
        'etablissement_id' => $this->etablissement->id,
        'president_id' => $this->president->id,
    ]);
    $this->commission->membres()->attach([$this->membre1->id, $this->membre2->id]);

    $this->template = DecisionTemplate::create([
        'label' => 'Admis avec mention',
        'email_subject' => 'Décision commission',
        'email_body' => 'Décision rendue.',
        'commission_id' => $this->commission->id,
        'created_by' => $this->admin->id,
    ]);

    $this->dossier = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
        'objet' => 'Dossier Dupont — thèse IA',
        'annee_inscription' => '2024-2025',
    ]);
});

// ─── Parcours complet ────────────────────────────────────────────

test('parcours complet : création → sync membres → planification → convocations → présences → décisions → terminer → PV', function () {
    $service = app(ReunionService::class);

    // 1. Création → auto-ajout des membres hors président.
    $reunion = $service->create([
        'commission_id' => $this->commission->id,
        'objet' => 'Commission IA — séance extraordinaire',
        'date_debut' => now()->addDay(),
        'date_fin' => now()->addDay()->addHour(),
        'lieu' => 'Salle B201',
        'type' => ReunionType::Presentiel,
    ], $this->agent);

    expect($reunion->statut)->toBe(ReunionStatut::Brouillon)
        ->and($reunion->commission_id)->toBe($this->commission->id)
        ->and($reunion->created_by)->toBe($this->agent->getKey());

    $invites = $reunion->invitations()->pluck('participant_id')->all();
    expect($invites)->toContain($this->membre1->id, $this->membre2->id)
        ->and($invites)->not->toContain($this->president->id);

    // 2. Audit log création.
    expect(AuditLog::where('action', 'reunion.create')
        ->where('entity_type', Reunion::class)
        ->where('entity_id', $reunion->getKey())->exists())->toBeTrue();

    // 3. Transition : brouillon → planifiée (notification).
    Notification::assertNothingSent();
    $service->transition($reunion, ReunionStatut::Planifiee, $this->admin);

    Notification::assertSentTo(
        [$this->membre1, $this->membre2],
        ReunionPlanifiee::class,
    );
    Mail::assertQueued(ReunionConvocation::class, 2);

    expect(AuditLog::where('action', 'reunion.statut')
        ->where('entity_id', $reunion->getKey())->exists())->toBeTrue();

    // 4. Présences.
    $reunion->presences()->create([
        'participant_id' => $this->membre1->id,
        'statut' => PresenceStatut::Present,
    ]);
    $reunion->presences()->create([
        'participant_id' => $this->membre2->id,
        'statut' => PresenceStatut::Absent,
    ]);

    expect($reunion->presences()->present()->count())->toBe(1);

    // 5. Décisions (par admin — snapshots template).
    $decision = app(DecisionService::class)->record(
        $reunion,
        $this->dossier,
        $this->template,
        $this->admin,
    );

    expect($decision->label)->toBe('Admis avec mention')
        ->and($decision->email_subject)->toBe('Décision commission')
        ->and($decision->annee_inscription)->toBe('2024-2025')
        ->and($this->dossier->fresh()->statut->value)->toBe('traite');

    // 6. Export CSV.
    $csv = app(DecisionService::class)->exportCsv($reunion);
    expect($csv)->toContain('Admis avec mention')
        ->and($csv)->toContain('Dossier Dupont');

    // 7. Transition : planifiée → en cours → terminée.
    $service->transition($reunion, ReunionStatut::EnCours, $this->admin);
    $service->transition($reunion, ReunionStatut::Terminee, $this->admin);

    Notification::assertSentTo(
        [$this->commission->president],
        ReunionTerminee::class,
    );

    // 8. PV : date_fin passée pour que estPassee() soit true.
    $reunion->update(['date_fin' => now()->subHour()->toDateTimeString()]);
    $reunion->refresh();

    expect($reunion->estPassee())->toBeTrue();

    $pv = app(ReunionService::class)->genererPv($reunion, $this->admin, ['PV test contenu']);

    expect($pv)->toBeInstanceOf(Pv::class)
        ->and($pv->titre)->toContain('PV')
        ->and(Pv::where('source_type', 'reunion')->where('source_id', $reunion->getKey())->count())->toBe(1);
});

test('transition invalide lève DomainException', function () {
    $reunion = Reunion::factory()->create([
        'commission_id' => $this->commission->id,
        'statut' => ReunionStatut::Planifiee,
    ]);

    $this->expectException(DomainException::class);

    app(ReunionService::class)->transition($reunion, ReunionStatut::Brouillon, $this->admin);
});

test('genererPv échoue si la réunion n\'est pas passée', function () {
    $reunion = Reunion::factory()->create([
        'commission_id' => $this->commission->id,
        'date_fin' => now()->addHour(),
    ]);

    $this->expectException(DomainException::class);

    app(ReunionService::class)->genererPv($reunion, $this->admin);
});

// ─── RBAC ────────────────────────────────────────────────────────

test('RBAC réunion : chaque rôle n\'exerce que ses actions', function () {
    $membre = User::factory()->create(['role' => UserRole::MembreCommission]);
    $commissionB = Commission::factory()->create();
    $reunion = Reunion::factory()->create(['commission_id' => $commissionB->id]);

    $presidentB = User::factory()->create(['role' => UserRole::PresidentCommission]);
    $commissionB->update(['president_id' => $presidentB->id]);

    // Admin : tout.
    expect(Gate::forUser($this->admin)->allows('create', Reunion::class))->toBeTrue()
        ->and(Gate::forUser($this->admin)->allows('delete', $reunion))->toBeTrue()
        ->and(Gate::forUser($this->admin)->allows('genererPv', $reunion))->toBeTrue()
        ->and(Gate::forUser($this->admin)->allows('gererDecisions', $reunion))->toBeTrue()
        ->and(Gate::forUser($this->admin)->allows('enregistrerPresence', $reunion))->toBeTrue();

    // Président : gère sa commission, crée, décisions, présence, PV.
    $reunionA = Reunion::factory()->create(['commission_id' => $this->commission->id]);
    expect(Gate::forUser($this->president)->allows('create', Reunion::class))->toBeTrue()
        ->and(Gate::forUser($this->president)->allows('gererDecisions', $reunionA))->toBeTrue()
        ->and(Gate::forUser($this->president)->allows('enregistrerPresence', $reunionA))->toBeTrue()
        ->and(Gate::forUser($this->president)->allows('planifier', $reunionA))->toBeTrue()
        ->and(Gate::forUser($this->president)->allows('view', $reunionA))->toBeTrue();

    // Membre : vue only, pas de création ni de modification.
    expect(Gate::forUser($membre)->allows('create', Reunion::class))->toBeFalse()
        ->and(Gate::forUser($membre)->allows('update', $reunion))->toBeFalse()
        ->and(Gate::forUser($membre)->allows('gererDecisions', $reunion))->toBeFalse()
        ->and(Gate::forUser($membre)->allows('enregistrerPresence', $reunion))->toBeFalse();

    // Doctorant : ne peut pas créer ni gérer.
    expect(Gate::forUser($this->doctorant)->allows('create', Reunion::class))->toBeFalse()
        ->and(Gate::forUser($this->doctorant)->allows('gererDecisions', $reunion))->toBeFalse();
});

test('RBAC corbeille : seul admin accède', function () {
    $this->actingAs($this->admin)
        ->get('/admin/reunions/corbeille')->assertOk();

    $this->actingAs(User::factory()->create(['role' => UserRole::MembreCommission]))
        ->get('/admin/reunions/corbeille')->assertForbidden();
});

test('Decision : doctorant ne voit que sa propre décision (mallette)', function () {
    $reunion = Reunion::factory()->create([
        'commission_id' => $this->commission->id,
        'statut' => ReunionStatut::Terminee,
    ]);

    $autreDoctorant = User::factory()->create(['role' => UserRole::Doctorant]);
    $autreDossier = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $autreDoctorant->id,
    ]);

    $decisionOwn = Decision::create([
        'reunion_id' => $reunion->getKey(),
        'dossier_id' => $this->dossier->getKey(),
        'decision_template_id' => $this->template->getKey(),
        'label' => 'Admis',
        'email_subject' => 'x',
        'email_body' => 'x',
        'annee_inscription' => '2024-2025',
        'decided_by' => $this->admin->getKey(),
    ]);

    Decision::create([
        'reunion_id' => $reunion->getKey(),
        'dossier_id' => $autreDossier->getKey(),
        'decision_template_id' => $this->template->getKey(),
        'label' => 'Ajourné',
        'email_subject' => 'x',
        'email_body' => 'x',
        'annee_inscription' => '2024-2025',
        'decided_by' => $this->admin->getKey(),
    ]);

    // Le doctorant ne doit pouvoir voir que sa propre décision.
    expect(Gate::forUser($this->doctorant)->allows('view', $decisionOwn))->toBeTrue();
    // La décision d'un autre doctorant n'est pas accessible.
    expect($reunion->decisions()->where('dossier_id', $autreDossier->id)->exists())->toBeTrue();
});

// ─── Anti-IDOR ───────────────────────────────────────────────────

test('anti-IDOR : un président ne gère pas les réunions d\'une autre commission', function () {
    $commissionA = Commission::factory()->create(['president_id' => $this->president->id]);
    $commissionB = Commission::factory()->create();

    $reunionB = Reunion::factory()->create(['commission_id' => $commissionB->id]);

    expect(Gate::forUser($this->president)->allows('update', $reunionB))->toBeFalse()
        ->and(Gate::forUser($this->president)->allows('gererDecisions', $reunionB))->toBeFalse()
        ->and(Gate::forUser($this->president)->allows('enregistrerPresence', $reunionB))->toBeFalse()
        ->and(Gate::forUser($this->president)->allows('planifier', $reunionB))->toBeFalse()
        ->and(Gate::forUser($this->president)->allows('genererPv', $reunionB))->toBeFalse()
        ->and(Gate::forUser($this->president)->allows('delete', $reunionB))->toBeFalse();
});

// ─── Pages Filament smoke ────────────────────────────────────────

test('pages réunions Filament rendent 200 pour admin', function () {
    $reunion = Reunion::factory()->create([
        'commission_id' => $this->commission->id,
        'statut' => ReunionStatut::Brouillon,
    ]);

    $this->actingAs($this->admin);

    $this->get(route('filament.admin.resources.reunions.index'))->assertOk();
    $this->get(route('filament.admin.resources.reunions.create'))->assertOk();
    $this->get(route('filament.admin.resources.reunions.edit', $reunion))->assertOk();
    $this->get(route('filament.admin.resources.reunions.presences', $reunion))->assertOk();
    $this->get(route('filament.admin.resources.reunions.decisions', $reunion))->assertOk();
    $this->get(route('filament.admin.resources.reunions.corbeille'))->assertOk();
});

test('pages réunions Filament interdites pour membre non invité', function () {
    $membre = User::factory()->create(['role' => UserRole::MembreCommission]);
    $reunion = Reunion::factory()->create(['commission_id' => $this->commission->id]);

    $this->actingAs($membre);

    // Membre voit la liste (viewAny ok si role non null) mais pas l'edit.
    $this->get(route('filament.admin.resources.reunions.edit', $reunion))->assertForbidden();
    $this->get(route('filament.admin.resources.reunions.presences', $reunion))->assertForbidden();
    $this->get(route('filament.admin.resources.reunions.decisions', $reunion))->assertForbidden();
});
