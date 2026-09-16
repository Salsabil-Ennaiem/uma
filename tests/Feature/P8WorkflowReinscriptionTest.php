<?php

use App\Enums\UserRole;
use App\Models\Commission;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\RapportEtat;
use App\Models\Universite;
use App\Models\User;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowInstance;
use App\Services\ArchiveService;
use App\Services\WorkflowEngine;
use Database\Seeders\WorkflowSeeder;
use DomainException;
use Illuminate\Support\Facades\Notification;
use SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder;

beforeEach(function () {
    Notification::fake();

    $this->universite = Universite::factory()->create();
    $this->ecole = EcoleDoctorale::factory()->create(['universite_id' => $this->universite->id]);
    $this->etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $this->ecole->id]);

    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->gestionnaire = User::factory()->create(['role' => UserRole::GestionnaireEcole]);
    $this->agent = User::factory()->create(['role' => UserRole::AgentAdministration]);
    $this->president = User::factory()->create(['role' => UserRole::PresidentCommission]);
    $this->doctorant = User::factory()->create(['role' => UserRole::Doctorant]);

    $this->commission = Commission::factory()->create([
        'etablissement_id' => $this->etablissement->id,
        'president_id' => $this->president->id,
    ]);

    $this->dossier = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
        'objet' => 'Dossier — ré-inscription',
        'annee_inscription' => '2eme',
    ]);

    (new WorkflowSeeder)->run();
    $this->engine = app(WorkflowEngine::class);
});

function demarrer_reinscription(object $test, int $niveau): WorkflowInstance
{
    return $test->engine->start(
        WorkflowDefinition::where('code', 'reinscription')->first(),
        $test->dossier,
        ['niveau' => $niveau],
        actor: $test->doctorant,
    );
}

function archiver_type(object $test, Dossier $dossier, string $type, string $semantique): void
{
    app(ArchiveService::class)->archiverContenu(
        $dossier,
        $type,
        $semantique,
        '%PDF-1.4 '.$type,
        'pdf',
        actor: $test->agent,
    );
}

// ─── Déclaration en base ────────────────────────────────────────

test('ré-inscription : le workflow est déclaré en base avec ses états', function () {
    $def = WorkflowDefinition::where('code', 'reinscription')->first();

    expect($def)->not->toBeNull()
        ->and($def->initial_state)->toBe('demande')
        ->and($def->is_active)->toBeTrue()
        ->and($def->states)->toContain('commission_examen', 'pv_valide', 'derogation_requise', 'derogation_accorde', 'archivee')
        ->and($def->transitions()->where('code', 'demander_derogation')->exists())->toBeTrue()
        ->and($def->transitions()->where('code', 'suite_inscription')->exists())->toBeTrue()
        ->and($def->transitions()->count())->toBe(11);
});

// ─── Parcours 2ᵉ année (branche directe) ────────────────────────

test('ré-inscription 2ᵉ année : parcours complet jusqu’à l’attestation et l’archivage', function () {
    (new DefaultPvTemplateSeeder)->run();
    RapportEtat::create([
        'label' => 'Attestation d\'inscription',
        'type' => 'etat',
        'pv_type' => 'rapport',
        'en_tete' => 'UNIVERSITÉ ALGIERS 1',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $instance = demarrer_reinscription($this, 2);

    $instance = $this->engine->apply($instance, 'valider_agent', [], $this->agent);
    expect($instance->current_state)->toBe('valide_agent');

    // Le rapport d'avancement est requis pour passer en commission.
    expect(fn () => $this->engine->apply($instance, 'inscrire_commission', [], $this->gestionnaire))
        ->toThrow(DomainException::class, 'rapport d\'avancement');

    archiver_type($this, $this->dossier, 'rapport_avancement', 'Rapport avancement');
    $instance = $this->engine->apply($instance, 'inscrire_commission', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('commission_examen');

    // Le PV de la commission est requis pour valider la décision.
    expect(fn () => $this->engine->apply($instance, 'valider_pv_commission', [], $this->president))
        ->toThrow(DomainException::class, 'procès-verbal');

    archiver_type($this, $this->dossier, 'pv', 'PV commission');
    $instance = $this->engine->apply($instance, 'valider_pv_commission', [], $this->president);
    expect($instance->current_state)->toBe('pv_valide');

    // Niveau 2 → poursuite directe vers le paiement, sans dérogation.
    $instance = $this->engine->apply($instance, 'suite_inscription', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('paiement_attendu')
        ->and($instance->dataGet('niveau'))->toBe(2);

    $instance = $this->engine->apply($instance, 'deposer_recu', [], $this->doctorant);
    expect($instance->current_state)->toBe('recu_uploaded');

    expect(fn () => $this->engine->apply($instance, 'valider_recu', [], $this->agent))
        ->toThrow(DomainException::class, 'reçu de paiement');

    $instance = $this->engine->apply($instance, 'valider_recu', ['reception_paiement' => true], $this->agent);
    expect($instance->current_state)->toBe('recu_valide');

    $instance = $this->engine->apply($instance, 'generer_attestation', [], $this->agent);
    expect($instance->current_state)->toBe('attestation_generee')
        ->and($this->dossier->fresh()->documents()->where('type', 'rapport')->count())->toBe(1);

    $instance = $this->engine->apply($instance, 'archiver', [], $this->admin);
    expect($instance->current_state)->toBe('archivee')
        ->and($instance->isCompleted())->toBeTrue();
});

// ─── Branche dérogation (niveau ≥ 4) ────────────────────────────

test('ré-inscription 5ᵉ année : la dérogation du président d’université est requise', function () {
    (new DefaultPvTemplateSeeder)->run();
    RapportEtat::create([
        'label' => 'Attestation d\'inscription',
        'type' => 'etat',
        'pv_type' => 'rapport',
        'en_tete' => 'UNIVERSITÉ ALGIERS 1',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $instance = demarrer_reinscription($this, 5);

    $instance = $this->engine->apply($instance, 'valider_agent', [], $this->agent);
    archiver_type($this, $this->dossier, 'rapport_avancement', 'Rapport avancement');
    $instance = $this->engine->apply($instance, 'inscrire_commission', [], $this->gestionnaire);
    archiver_type($this, $this->dossier, 'pv', 'PV commission');
    $instance = $this->engine->apply($instance, 'valider_pv_commission', [], $this->president);
    expect($instance->current_state)->toBe('pv_valide');

    // Niveau 5 → la branche directe est refusée, la dérogation devient obligatoire.
    expect(fn () => $this->engine->apply($instance, 'suite_inscription', [], $this->gestionnaire))
        ->toThrow(DomainException::class, 'dérogation');

    $instance = $this->engine->apply($instance, 'demander_derogation', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('derogation_requise');

    expect(fn () => $this->engine->apply($instance, 'approuver_derogation', [], $this->doctorant))
        ->toThrow(DomainException::class, 'non autorisé');

    $instance = $this->engine->apply($instance, 'approuver_derogation', [], $this->admin);
    expect($instance->current_state)->toBe('derogation_accorde');

    $instance = $this->engine->apply($instance, 'suite_paiement_derogation', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('paiement_attendu');

    $instance = $this->engine->apply($instance, 'deposer_recu', [], $this->doctorant);
    $instance = $this->engine->apply($instance, 'valider_recu', ['reception_paiement' => true], $this->agent);
    $instance = $this->engine->apply($instance, 'generer_attestation', [], $this->agent);
    $instance = $this->engine->apply($instance, 'archiver', [], $this->admin);

    expect($instance->current_state)->toBe('archivee')
        ->and($instance->isCompleted())->toBeTrue();
});

// ─── Gardes de niveau ───────────────────────────────────────────

test('ré-inscription 3ᵉ année : garde de niveau sélectionne la branche directe', function () {
    $instance = demarrer_reinscription($this, 3);

    $instance = $this->engine->apply($instance, 'valider_agent', [], $this->agent);
    archiver_type($this, $this->dossier, 'rapport_avancement', 'Rapport avancement');
    $instance = $this->engine->apply($instance, 'inscrire_commission', [], $this->gestionnaire);
    archiver_type($this, $this->dossier, 'pv', 'PV commission');
    $instance = $this->engine->apply($instance, 'valider_pv_commission', [], $this->president);
    expect($instance->current_state)->toBe('pv_valide');

    // Niveau 3 → la dérogation est refusée par la garde de niveau, puis la branche directe s'applique.
    expect(fn () => $this->engine->apply($instance, 'demander_derogation', [], $this->gestionnaire))
        ->toThrow(DomainException::class, 'dérogation');

    $instance = $this->engine->apply($instance, 'suite_inscription', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('paiement_attendu');
});

// ─── Garde de rôle ──────────────────────────────────────────────

test('ré-inscription : un doctorant ne peut pas valider un dossier', function () {
    $instance = demarrer_reinscription($this, 2);

    expect(fn () => $this->engine->apply($instance, 'valider_agent', [], $this->doctorant))
        ->toThrow(DomainException::class, 'non autorisé');
});