<?php

use App\Enums\UserRole;
use App\Models\Commission;
use App\Models\DecisionTemplate;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Universite;
use App\Models\User;
use App\PvRules\ApprovalRules;
use App\PvRules\ParticipantResolver;
use App\PvRules\PvRules;
use App\PvSignatures\SignatureResolver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use SalsabilEnnaiem\PvModule\Contracts\ApprovalRules as ApprovalRulesContract;
use SalsabilEnnaiem\PvModule\Contracts\CanManagePv;
use SalsabilEnnaiem\PvModule\Contracts\ParticipantResolver as ParticipantResolverContract;
use SalsabilEnnaiem\PvModule\Models\Pv;
use SalsabilEnnaiem\PvModule\Models\PvValidation;
use SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder;
use SalsabilEnnaiem\PvModule\Services\PvService;
use SalsabilEnnaiem\PvModule\Services\SignatureService;

$signaturePng = static function (): string {
    return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
};

$storeSignature = static function (User $user) use ($signaturePng): void {
    app(SignatureService::class)->storeFromBase64($signaturePng(), $user);
};

beforeEach(function () use ($storeSignature) {
    (new DefaultPvTemplateSeeder)->run();
    $this->storeSignature = $storeSignature;
});

test('les 3 contrats du package sont liés aux classes métier UMA', function () {
    expect(config('pv-module.can_manage_pv'))->toBe(PvRules::class)
        ->and(config('pv-module.approval_rules'))->toBe(ApprovalRules::class)
        ->and(config('pv-module.participant_resolver'))->toBe(ParticipantResolver::class);

    expect(app(CanManagePv::class))->toBeInstanceOf(PvRules::class)
        ->and(app(ApprovalRulesContract::class))->toBeInstanceOf(ApprovalRules::class)
        ->and(app(ParticipantResolverContract::class))->toBeInstanceOf(ParticipantResolver::class);
});

test('RBAC fail-closed : chaque rôle n’exerce que ses actions (contrat CanManagePv)', function () {
    $president = User::factory()->create(['role' => UserRole::PresidentCommission]);
    $agent = User::factory()->create(['role' => UserRole::AgentAdministration]);
    $membre = User::factory()->create(['role' => UserRole::MembreCommission]);
    $directeur = User::factory()->create(['role' => UserRole::DirecteurThese]);
    $doctorant = User::factory()->create(['role' => UserRole::Doctorant]);
    $inconnu = User::factory()->create(); // role null -> fail-closed

    $rules = app(CanManagePv::class);

    expect($rules->canCreate($president))->toBeTrue()
        ->and($rules->canCreate($agent))->toBeTrue()
        ->and($rules->canCreate($membre))->toBeFalse()
        ->and($rules->canCreate($directeur))->toBeFalse()
        ->and($rules->canCreate($doctorant))->toBeFalse()
        ->and($rules->canCreate($inconnu))->toBeFalse()
        ->and($rules->canCreate(null))->toBeFalse()
        ->and($rules->canManageTemplates($doctorant))->toBeFalse()
        ->and($rules->canManageTemplates($president))->toBeTrue();
});

test('aucune action document possible sans la garde du package (route 403)', function () {
    $doctorant = User::factory()->create(['role' => UserRole::Doctorant]);

    $this->actingAs($doctorant)
        ->post(route('pv-module.store'), [
            'titre' => 'Tentative interdite',
            'contenu' => ['x'],
            'type' => 'pv',
        ])
        ->assertForbidden();
});

test('SIGNATURE_DRIVER=qualified change le comportement sans toucher à la logique', function () {
    Log::spy();

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $receiver = User::factory()->create(['role' => UserRole::MembreCommission]);
    ($this->storeSignature)($admin);
    ($this->storeSignature)($receiver);

    $this->actingAs($admin)
        ->post(route('pv-module.store'), [
            'titre' => 'PV driver signature',
            'contenu' => ['Contenu.'],
            'type' => 'pv',
        ])
        ->assertRedirect();

    $pv = Pv::query()->firstOrFail();

    $this->actingAs($admin)
        ->post(route('pv-module.send', $pv), ['receivers' => [$receiver->getKey()]])
        ->assertRedirect();

    config(['uma.compliance.signature_driver' => 'qualified']);

    $this->actingAs($receiver)
        ->post(route('pv-module.sign', $pv))
        ->assertForbidden();

    Log::shouldHaveReceived('warning')
        ->withArgs(fn (string $message) => str_contains($message, 'certificat requis'))
        ->once();

    config(['uma.compliance.qualified.allow_without_certificate' => true]);

    $this->actingAs($receiver)
        ->post(route('pv-module.sign', $pv))
        ->assertRedirect();
});

test('le resolver retourne toujours mécanisme + horodatage (trace R2)', function () {
    $user = User::factory()->create(['role' => UserRole::MembreCommission]);
    ($this->storeSignature)($user);

    config(['uma.compliance.signature_driver' => 'simple_image']);
    $token = app(SignatureResolver::class)->strategy()->signToken($user);

    expect($token['mechanism'])->toBe('simple_image')
        ->and(isset($token['signed_at']))->toBeTrue();

    config(['uma.compliance.signature_driver' => 'qualified']);
    $tokenQ = app(SignatureResolver::class)->strategy()->signToken($user);

    expect($tokenQ['mechanism'])->toBe('qualified');
});

test('ApprovalRules : les seuils sont paramétrables (unanime vs quorum)', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $pv = app(PvService::class)->store([
        'titre' => 'PV quorum',
        'contenu' => ['Contenu.'],
        'type' => 'pv',
    ], $admin);

    $a = User::factory()->create(['role' => UserRole::MembreCommission]);
    $b = User::factory()->create(['role' => UserRole::MembreCommission]);

    foreach ([$a, $b] as $user) {
        PvValidation::create([
            'pv_id' => $pv->id,
            'user_id' => $user->getKey(),
            'version' => 1,
            'statut' => PvValidation::STATUT_EN_ATTENTE,
        ]);
    }

    PvValidation::where('user_id', $a->getKey())->update(['statut' => PvValidation::STATUT_VALIDE]);

    config(['uma.approval.mode' => 'unanimous']);
    expect($pv->refresh()->estCompletementValide())->toBeFalse();

    config(['uma.approval.mode' => 'quorum', 'uma.approval.quorum_percent' => 50]);
    expect($pv->refresh()->estCompletementValide())->toBeTrue();

    config(['uma.approval.mode' => 'quorum', 'uma.approval.quorum_percent' => 80]);
    expect($pv->refresh()->estCompletementValide())->toBeFalse();
});

test('ParticipantResolver résout les signataires depuis la commission métier', function () {
    $universite = Universite::factory()->create();
    $ecole = EcoleDoctorale::factory()->create(['universite_id' => $universite->id]);
    $etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $ecole->id]);

    $president = User::factory()->create(['role' => UserRole::PresidentCommission]);
    $m1 = User::factory()->create(['role' => UserRole::MembreCommission]);
    $m2 = User::factory()->create(['role' => UserRole::MembreCommission]);

    $commission = Commission::factory()->create([
        'etablissement_id' => $etablissement->id,
        'president_id' => $president->id,
    ]);
    $commission->membres()->attach([$m1->id, $m2->id]);

    $resolver = app(ParticipantResolverContract::class);
    $ids = $resolver->resolveParticipants($president, ['type' => 'commission', 'commission_id' => $commission->id]);

    expect($ids)->toContain($president->id, $m1->id, $m2->id);

    $passthrough = $resolver->resolveParticipants($president, ['participants' => [7, 8]]);
    expect($passthrough)->toBe([7, 8]);

    $jury = $resolver->resolveParticipants($president, ['type' => 'jury', 'membres' => [$m1->id]]);
    expect($jury)->toBe([$m1->id]);
});

test('la hiérarchie Université → École doctorale → Établissement → Commission est opérationnelle', function () {
    $universite = Universite::factory()->create();
    $ecole = EcoleDoctorale::factory()->create(['universite_id' => $universite->id]);
    $etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $ecole->id]);
    $commission = Commission::factory()->create(['etablissement_id' => $etablissement->id]);

    expect($commission->etablissement->ecoleDoctorale->universite->id)->toBe($universite->id)
        ->and($universite->ecoleDoctorales()->count())->toBe(1)
        ->and($etablissement->commissions()->count())->toBe(1);

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->actingAs($admin);

    foreach (['/admin/universites', '/admin/ecole-doctorales', '/admin/etablissements', '/admin/commissions'] as $url) {
        $this->get($url)->assertOk();
    }
});

test('anti-IDOR : un président ne peut pas sortir de sa commission', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $presidentA = User::factory()->create(['role' => UserRole::PresidentCommission]);

    $commissionA = Commission::factory()->create(['president_id' => $presidentA->id]);
    $commissionB = Commission::factory()->create();

    $tplA = DecisionTemplate::create([
        'label' => 'Décision A',
        'email_subject' => 'Sujet A',
        'email_body' => 'Corps A',
        'commission_id' => $commissionA->id,
        'created_by' => $admin->id,
    ]);

    $tplB = DecisionTemplate::create([
        'label' => 'Décision B',
        'email_subject' => 'Sujet B',
        'email_body' => 'Corps B',
        'commission_id' => $commissionB->id,
        'created_by' => $admin->id,
    ]);

    expect(Gate::forUser($presidentA)->allows('view', $commissionA))->toBeTrue()
        ->and(Gate::forUser($presidentA)->allows('view', $commissionB))->toBeFalse()
        ->and(Gate::forUser($presidentA)->allows('update', $commissionB))->toBeFalse()
        ->and(Gate::forUser($presidentA)->allows('update', $tplA))->toBeTrue()
        ->and(Gate::forUser($presidentA)->allows('update', $tplB))->toBeFalse()
        ->and(Gate::forUser($presidentA)->allows('delete', $commissionB))->toBeFalse();
});

test('anti-IDOR : un président ne gère pas les PV d’une autre commission', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $presidentB = User::factory()->create(['role' => UserRole::PresidentCommission]);

    $commissionA = Commission::factory()->create();

    $pv = app(PvService::class)->store([
        'titre' => 'PV commission A',
        'contenu' => ['Contenu.'],
        'type' => 'pv',
        'source_type' => 'commission',
        'source_id' => $commissionA->id,
    ], $admin);

    $this->actingAs($presidentB)
        ->post(route('pv-module.send', $pv), ['receivers' => [$presidentB->getKey()]])
        ->assertForbidden();
});
