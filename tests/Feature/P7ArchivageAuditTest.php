<?php

use App\Enums\ReunionStatut;
use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\Decision;
use App\Models\Document;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\RapportEtat;
use App\Models\Reunion;
use App\Models\Universite;
use App\Models\User;
use App\Policies\AuditLogPolicy;
use App\Services\ArchiveService;
use App\Services\RapportService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use LogicException;
use SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder;

beforeEach(function () {
    Storage::fake('public');

    $this->universite = Universite::factory()->create();
    $this->ecole = EcoleDoctorale::factory()->create(['universite_id' => $this->universite->id]);
    $this->etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $this->ecole->id]);

    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->gestionnaire = User::factory()->create(['role' => UserRole::GestionnaireEcole]);
    $this->agent = User::factory()->create(['role' => UserRole::AgentAdministration]);
    $this->doctorant = User::factory()->create(['role' => UserRole::Doctorant]);

    $this->commission = Commission::factory()->create([
        'etablissement_id' => $this->etablissement->id,
        'president_id' => $this->agent->id,
    ]);

    $this->dossier = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
        'objet' => 'Dossier Martin — apprentissage machine',
        'annee_inscription' => '3eme',
    ]);
});

// ─── Audit immuable (rules d'or P7) ─────────────────────────────

test('audit_logs est append-only : update, delete et save sur existant sont refusés', function () {
    $log = AuditLog::log('test.immutable', $this->dossier);

    expect(fn () => $log->update(['action' => 'pirate']))
        ->toThrow(LogicException::class)
        ->and(fn () => $log->refresh()->delete())
        ->toThrow(LogicException::class);
});

test('AuditLogPolicy : l’administrateur peut lire mais jamais modifier le journal', function () {
    $log = AuditLog::log('test.policy', $this->dossier, actor: $this->admin);

    $policy = app(AuditLogPolicy::class);

    expect($policy->view($this->admin, $log))->toBeTrue()
        ->and($policy->view($this->doctorant, $log))->toBeFalse()
        ->and($policy->viewAny($this->doctorant))->toBeFalse()
        ->and($policy->create($this->admin))->toBeFalse()
        ->and($policy->update($this->admin, $log))->toBeFalse()
        ->and($policy->delete($this->admin, $log))->toBeFalse();

    // Le Gate `before` donne un accès « super administrateur », mais la garde modèle
    // reste l'ultime barrière : toute écriture lève une LogicException.
    expect(fn () => $log->update(['action' => 'pirate']))
        ->toThrow(LogicException::class)
        ->and(fn () => $log->delete())
        ->toThrow(LogicException::class);
});

test('une ligne du journal d’audit est écrite à chaque document archivé', function () {
    $service = app(ArchiveService::class);

    $service->archiverContenu($this->dossier, 'attestation', 'Attestation de réussite', '%PDF-1.7 fic', 'pdf', null, [], null, null, $this->admin);

    expect(AuditLog::query()->where('action', 'document.create')->count())->toBe(1)
        ->and(AuditLog::query()->where('entity_type', Document::class)->first()?->user_id)->toBe($this->admin->id);
});

// ─── Mallette (dossier numérique) ───────────────────────────────

test('archiverContenu stocke la pièce sur le disque public et la rattache au dossier', function () {
    $contenu = '%PDF-1.7 attestation';

    $document = app(ArchiveService::class)->archiverContenu(
        $this->dossier,
        'attestation',
        'Attestation de réussite',
        $contenu,
        'pdf',
        null,
        ['mots_cles' => ['admissibilite']],
        actor: $this->admin,
    );

    $v1 = $document->lastVersion();

    expect($document->documentable_id)->toBe($this->dossier->id)
        ->and($document->is_archived)->toBeTrue()
        ->and($document->retention_months)->toBe(120)
        ->and($document->retention_until?->toDateString())->toBe(now()->addMonths(120)->toDateString())
        ->and($document->versions()->count())->toBe(1)
        ->and($v1->version)->toBe(1)
        ->and($v1->hash)->toBe(hash('sha256', $contenu))
        ->and($v1->file_path)->toContain('documents/dossier/'.$this->dossier->id)
        ->and(Storage::disk('public')->exists($v1->file_path))->toBeTrue()
        ->and($this->dossier->documents()->count())->toBe(1)
        ->and($this->dossier->fresh()->documents()->first()->type)->toBe('attestation');
});

test('les versions d’un document sont immuables et jamais écrasées', function () {
    $service = app(ArchiveService::class);

    $document = $service->archiverContenu($this->dossier, 'these', 'Manuscrit thèse', 'V1', 'pdf', null, [], null, null, $this->admin);

    $v2 = $service->nouvelleVersionContenu($document, 'V2', 'these.pdf', 'application/pdf', actor: $this->admin);

    $document->refresh();

    expect($document->versions()->count())->toBe(2)
        ->and($document->lastVersion()->id)->toBe($v2->id)
        ->and($v2->hash)->toBe(hash('sha256', 'V2'))
        ->and($document->versions->get(0)->hash)->toBe(hash('sha256', 'V1'))   // v1 intacte
        ->and($document->versions->unique('hash')->count())->toBe(2)
        ->and(AuditLog::query()->where('action', 'document.version.create')->count())->toBe(1);

    expect(fn () => $v2->update(['size' => 0]))
        ->toThrow(LogicException::class)
        ->and(fn () => $v2->delete())
        ->toThrow(LogicException::class);

    $v1 = $document->versions->first();
    expect(fn () => $v1->save())
        ->toThrow(LogicException::class);
});

test('la rétention par défaut suit la politique d’archivage du CDC', function () {
    $service = app(ArchiveService::class);

    expect($service->retentionParDefaut('attestation'))->toBe(120)
        ->and($service->retentionParDefaut('pv'))->toBe(120)
        ->and($service->retentionParDefaut('decision'))->toBe(120)
        ->and($service->retentionParDefaut('these'))->toBe(360)
        ->and($service->retentionParDefaut('recu'))->toBe(60);

    $these = $service->archiverContenu($this->dossier, 'these', 'Thèse', 'PDF', 'pdf', actor: $this->admin);
    $recu = $service->archiverContenu($this->dossier, 'recu', 'Reçu', 'PDF', 'pdf', actor: $this->admin);

    expect($these->retention_months)->toBe(360)
        ->and($recu->retention_months)->toBe(60);
});

test('chaque pièce de la mallette dispose d’une entrée de suivi de rétention', function () {
    $service = app(ArchiveService::class);

    $service->archiverContenu($this->dossier, 'attestation', 'Attestation', 'PDF', 'pdf', actor: $this->admin);

    $this->dossier->refresh();

    expect($this->dossier->documents()->count())->toBe(1)
        ->and($this->dossier->documents()->first()->retention_until)->not->toBeNull();
});

test('les décisions de la mallette sont filtrables par année d’inscription', function () {
    $reunion = Reunion::factory()->create([
        'commission_id' => $this->commission->id,
        'statut' => ReunionStatut::Terminee,
    ]);

    $decision2024 = Decision::create([
        'reunion_id' => $reunion->id,
        'dossier_id' => $this->dossier->id,
        'label' => 'Admis — année 1',
        'annee_inscription' => '2024-2025',
        'decided_by' => $this->agent->id,
    ]);
    $decision2026 = Decision::create([
        'reunion_id' => $reunion->id,
        'dossier_id' => $this->dossier->id,
        'label' => 'Admis — année 3',
        'annee_inscription' => '2026-2027',
        'decided_by' => $this->agent->id,
    ]);

    $annees = Decision::query()
        ->where('dossier_id', $this->dossier->id)
        ->distinct()
        ->orderBy('annee_inscription')
        ->pluck('annee_inscription')
        ->all();

    expect($annees)->toBe(['2024-2025', '2026-2027'])
        ->and(Decision::query()->where('dossier_id', $this->dossier->id)->where('annee_inscription', '2024-2025')->first()->label)
        ->toBe($decision2024->label)
        ->and(Decision::query()->where('dossier_id', $this->dossier->id)->where('annee_inscription', '2026-2027')->first()->label)
        ->toBe($decision2026->label);
});

// ─── Rapports et états (batch 50 attestations) ──────────────────

test('un rapport/état se configure avec marges, orientation et en-tête', function () {
    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'pv',
        'en_tete' => 'UNIVERSITÉ ALGIERS 1 — SCOLARITÉ',
        'orientation' => 'landscape',
        'format_papier' => 'A4',
        'marges' => ['top' => 25, 'bottom' => 10],
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    expect($etat->orientation)->toBe('landscape')
        ->and($etat->margesEffectives())->toMatchArray(['top' => 25, 'bottom' => 10, 'left' => 20, 'right' => 20])
        ->and(Gate::forUser($this->gestionnaire)->allows('create', RapportEtat::class))->toBeTrue()
        ->and(Gate::forUser($this->agent)->allows('update', $etat))->toBeFalse()
        ->and(Gate::forUser($this->agent)->allows('generer', $etat))->toBeTrue()
        ->and(Gate::forUser($this->doctorant)->allows('generer', $etat))->toBeFalse();
});

test('génération et archivage automatique d’un rapport (pièce dans la mallette)', function () {
    (new DefaultPvTemplateSeeder)->run();

    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'rapport',
        'en_tete' => 'ALGIERS 1',
        'orientation' => 'portrait',
        'format_papier' => 'A4',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $resultat = app(RapportService::class)->generer($etat, $this->dossier, $this->agent);

    expect($resultat['pdf'])->toBeString()
        ->and(substr($resultat['pdf'], 0, 4))->toBe('%PDF')
        ->and($resultat['document'])->not->toBeNull()
        ->and($resultat['document']->documentable_id)->toBe($this->dossier->id)
        ->and($this->dossier->fresh()->documents()->where('type', 'rapport')->count())->toBe(1)
        ->and(AuditLog::query()->where('action', 'rapport.generer')->count())->toBe(1);

    $pv = $resultat['pv'];
    expect($pv->source_type)->toBe('rapport_etat')
        ->and($pv->source_id)->toBe($etat->id);
});

test('aperçu HTML rendu par le moteur du package (aucune sortie maison)', function () {
    (new DefaultPvTemplateSeeder)->run();

    $etat = RapportEtat::create([
        'label' => 'État des inscriptions',
        'type' => 'rapport',
        'pv_type' => 'pv',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $html = app(RapportService::class)->apercuHtml($etat, $this->dossier, $this->agent);

    expect($html)->toBeString()
        ->and($html)->toContain('État des inscriptions')
        ->and(AuditLog::query()->count())->toBe(0);   // un aperçu ne crée aucune pièce
});

test('impression en masse : un lot de 50 attestations est généré et archivé', function () {
    ini_set('memory_limit', '1G');
    (new DefaultPvTemplateSeeder)->run();

    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'rapport',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $dossiers = Dossier::factory()->count(50)->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
    ]);

    $resultat = app(RapportService::class)->genererEnMasse($etat, $dossiers, $this->agent);

    expect($resultat['total'])->toBe(50)
        ->and($resultat['reussites'])->toBe(50)
        ->and($resultat['echecs'])->toBe(0)
        ->and(count($resultat['documents']))->toBe(50)
        ->and(count($resultat['erreurs']))->toBe(0);

    foreach ($dossiers->take(3) as $dossier) {
        expect($dossier->fresh()->documents()->count())->toBe(1);
    }
});

test('impression en masse : un dossier en échec n’interrompt pas le lot', function () {
    (new DefaultPvTemplateSeeder)->run();

    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'rapport',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $dossiers = Dossier::factory()->count(5)->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
    ]);

    // Le premier dossier est désactivé : sa génération échoue mais ne stoppe pas le lot.
    $etatInactif = $etat->replicate(['is_active'])->fill(['is_active' => false]);
    $etatInactif->save();

    $resultat = app(RapportService::class)->genererEnMasse($etatInactif, $dossiers, $this->agent);

    expect($resultat['total'])->toBe(5)
        ->and($resultat['reussites'])->toBe(0)
        ->and($resultat['echecs'])->toBe(5)
        ->and(count($resultat['erreurs']))->toBe(5);
});

test('routes web : aperçu HTML et PDF sont accessibles par un rôle autorisé', function () {
    (new DefaultPvTemplateSeeder)->run();

    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'pv',
        'en_tete' => 'ALGIERS 1',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $this->actingAs($this->agent)
        ->get(route('admin.rapports-etats.apercu', ['etat' => $etat, 'dossier' => $this->dossier->id]))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/html; charset=utf-8');

    $response = $this->actingAs($this->agent)
        ->get(route('admin.rapports-etats.pdf', ['etat' => $etat, 'dossier' => $this->dossier->id]));

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
    expect(substr($response->getContent(), 0, 4))->toBe('%PDF');
});

test('routes web : un rôle non autorisé est bloqué', function () {
    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'pv',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $this->actingAs($this->doctorant)
        ->get(route('admin.rapports-etats.pdf', ['etat' => $etat, 'dossier' => $this->dossier->id]))
        ->assertForbidden();
});

test('pages Filament rapport-etats rendent 200 pour admin', function () {
    $etat = RapportEtat::create([
        'label' => 'Attestation de scolarité',
        'type' => 'etat',
        'pv_type' => 'pv',
        'en_tete' => 'ALGIERS 1',
        'is_active' => true,
        'created_by' => $this->admin->id,
    ]);

    $this->actingAs($this->admin);

    $this->get(route('filament.admin.resources.rapport-etats.index'))->assertOk();
    $this->get(route('filament.admin.resources.rapport-etats.create'))->assertOk();
    $this->get(route('filament.admin.resources.rapport-etats.edit', $etat))->assertOk();
});
