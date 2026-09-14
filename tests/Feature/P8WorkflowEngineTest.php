<?php

use App\Enums\UserRole;
use App\Models\Commission;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\RapportEtat;
use App\Models\Reclamation;
use App\Models\Reservation;
use App\Models\Universite;
use App\Models\User;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowGuard;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
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
        'objet' => 'Dossier — workflow paramétrable',
        'annee_inscription' => '3eme',
    ]);

    (new WorkflowSeeder)->run();
    $this->engine = app(WorkflowEngine::class);
});

// ─── Configuration des ActiveStates ─────────────────────────────

test('les trois workflows du CDC sont paramétrés en base', function () {
    expect(WorkflowDefinition::query()->pluck('code')->all())
        ->toContain('inscription', 'soutenance', 'reclamation')
        ->and(WorkflowDefinition::where('code', 'inscription')->first()->initial_state)->toBe('demande')
        ->and(WorkflowDefinition::where('code', 'soutenance')->first()->initial_state)->toBe('depot')
        ->and(WorkflowDefinition::where('code', 'reclamation')->first()->initial_state)->toBe('ouverte');
});

test('une transition peut être ajoutée en base sans aucune modification de code', function () {
    // Nouveau workflow déclaré uniquement en base (aucun switch/case dans le moteur).
    $definition = WorkflowDefinition::create([
        'code' => 'bourse',
        'name' => 'Bourse doctorale',
        'subject_type' => Dossier::class,
        'states' => ['depot', 'instruction', 'accordee'],
        'initial_state' => 'depot',
        'is_active' => true,
    ]);

    WorkflowTransition::create([
        'workflow_definition_id' => $definition->id,
        'code' => 'instruire',
        'label' => 'Instruction',
        'from_state' => 'depot',
        'to_state' => 'instruction',
        'roles' => [UserRole::AgentAdministration->value],
        'is_active' => true,
    ]);
    WorkflowTransition::create([
        'workflow_definition_id' => $definition->id,
        'code' => 'accorder',
        'label' => 'Accorder la bourse',
        'from_state' => 'instruction',
        'to_state' => 'accordee',
        'roles' => [UserRole::Admin->value],
        'is_active' => true,
    ]);

    $instance = $this->engine->start($definition, $this->dossier, actor: $this->admin);

    $instance = $this->engine->apply($instance, 'instruire', [], $this->agent);
    expect($instance->current_state)->toBe('instruction');

    $instance = $this->engine->apply($instance, 'accorder', [], $this->admin);
    expect($instance->current_state)->toBe('accordee')
        ->and($instance->isCompleted())->toBeTrue();
});

test('une transition non déclarée est refusée sans lever exception côté UI', function () {
    $def = WorkflowDefinition::where('code', 'inscription')->first();
    $instance = $this->engine->start($def, $this->dossier, actor: $this->admin);

    expect(fn () => $this->engine->apply($instance, 'transition_inexistante', [], $this->agent))
        ->toThrow(DomainException::class);
});

// ─── Workflow A : inscription doctorale ─────────────────────────

test('workflow A : parcours complet jusqu’à l’attestation et l’archivage', function () {
    (new DefaultPvTemplateSeeder)->run();
    RapportEtat::create([
        'label' => 'Attestation d\'inscription',
        'type' => 'etat',
        'pv_type' => 'rapport',
        'en_tete' => 'UNIVERSITÉ ALGIERS 1',
        'is_active' => true,
        'created_by' => $this->gestionnaire->id,
    ]);

    $instance = $this->engine->start(
        WorkflowDefinition::where('code', 'inscription')->first(),
        $this->dossier,
        actor: $this->doctorant,
    );

    $instance = $this->engine->apply($instance, 'valider_agent', [], $this->agent);
    expect($instance->current_state)->toBe('valide_agent');

    // Le rôle non autorisé est bloqué.
    expect(fn () => $this->engine->apply($instance, 'valider_agent', [], $this->doctorant))
        ->toThrow(DomainException::class);

    $instance = $this->engine->apply($instance, 'demander_paiement', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('paiement_attendu');

    $instance = $this->engine->apply($instance, 'deposer_recu', [], $this->doctorant);
    expect($instance->current_state)->toBe('recu_uploaded');

    // Le reçu est vérifié via la donnée de transition (paiement système vérifié).
    $instance = $this->engine->apply(
        $instance,
        'valider_recu',
        ['reception_paiement' => true],
        $this->agent,
    );
    expect($instance->current_state)->toBe('recu_valide');

    $instance = $this->engine->apply($instance, 'generer_attestation', [], $this->agent);
    expect($instance->current_state)->toBe('attestation_generee')
        ->and($this->dossier->fresh()->documents()->where('type', 'rapport')->count())->toBe(1);

    $instance = $this->engine->apply($instance, 'archiver', [], $this->admin);
    expect($instance->current_state)->toBe('archivee')
        ->and($instance->isCompleted())->toBeTrue()
        ->and($instance->auditTrails()->where('transition_code', 'archiver')->count())->toBe(1);
});

test('workflow A : le reçu non vérifié bloque la validation', function () {
    $instance = $this->engine->start(
        WorkflowDefinition::where('code', 'inscription')->first(),
        $this->dossier,
        actor: $this->doctorant,
    );

    $instance = $this->engine->apply($instance, 'valider_agent', [], $this->agent);
    $instance = $this->engine->apply($instance, 'demander_paiement', [], $this->gestionnaire);
    $instance = $this->engine->apply($instance, 'deposer_recu', [], $this->doctorant);

    expect(fn () => $this->engine->apply($instance, 'valider_recu', [], $this->agent))
        ->toThrow(DomainException::class, 'reçu de paiement');
});

// ─── Workflow B : soutenance (éligibilité, rapporteurs, jury, planification) ──

test('workflow B : l’éligibilité exige inscriptions, crédits et rapport d’approbation', function () {
    $instance = $this->engine->start(
        WorkflowDefinition::where('code', 'soutenance')->first(),
        $this->dossier,
        actor: $this->doctorant,
    );

    // Crédits non validés → refus.
    expect(fn () => $this->engine->apply($instance, 'valider_depot', ['inscriptions_count' => 3], $this->agent))
        ->toThrow(DomainException::class, 'crédits');

    // Document d'approbation manquant → refus.
    expect(fn () => $this->engine->apply($instance, 'valider_depot', [
        'inscriptions_count' => 3,
        'credits_valides' => true,
    ], $this->agent))
        ->toThrow(DomainException::class, 'rapport d\'approbation');

    // Éligibilité satisfaite.
    app(ArchiveService::class)->archiverContenu(
        $this->dossier,
        'rapport_approbation',
        'Approbation',
        '%PDF-1.4 approbation',
        'pdf',
        actor: $this->agent,
    );

    $instance = $this->engine->apply($instance, 'valider_depot', [
        'inscriptions_count' => 3,
        'credits_valides' => true,
    ], $this->agent);

    expect($instance->current_state)->toBe('depot_valide');
});

test('workflow B : parcours complet jusqu’au diplôme disponible', function () {
    app(ArchiveService::class)->archiverContenu(
        $this->dossier,
        'rapport_approbation',
        'Approbation',
        '%PDF-1.4 approbation',
        'pdf',
        actor: $this->agent,
    );

    $instance = $this->engine->start(
        WorkflowDefinition::where('code', 'soutenance')->first(),
        $this->dossier,
        actor: $this->doctorant,
    );

    $instance = $this->engine->apply($instance, 'valider_depot', [
        'inscriptions_count' => 3,
        'credits_valides' => true,
    ], $this->agent);

    $instance = $this->engine->apply($instance, 'designer_rapporteurs', [], $this->president);
    $instance = $this->engine->apply($instance, 'enregistrer_rapports', ['rapporteurs_favorables' => 2], $this->agent);
    $instance = $this->engine->apply($instance, 'designer_jury', [], $this->president);
    $instance = $this->engine->apply($instance, 'envoyer_rectorat', [], $this->agent);

    // Arrêté manquant → refus.
    expect(fn () => $this->engine->apply($instance, 'valider_arrete', [], $this->admin))
        ->toThrow(DomainException::class, 'arrêté');

    app(ArchiveService::class)->archiverContenu(
        $this->dossier,
        'arrete',
        'Arrêté jury',
        '%PDF-1.4 arrete',
        'pdf',
        actor: $this->admin,
    );
    $instance = $this->engine->apply($instance, 'valider_arrete', [], $this->admin);

    $juryA = User::factory()->create(['role' => UserRole::MembreCommission]);
    $juryB = User::factory()->create(['role' => UserRole::MembreCommission]);

    $debut = now()->addDays(15)->setTime(9, 0);
    $fin = $debut->copy()->addHours(3);

    $instance = $this->engine->apply($instance, 'planifier_soutenance', [
        'salle' => 'Salle A',
        'soutenance_date' => $debut,
        'soutenance_fin' => $fin,
        'jury_membres' => [$juryA->id, $juryB->id],
    ], $this->gestionnaire);

    expect($instance->current_state)->toBe('planification')
        ->and(Reservation::where('type', Reservation::TYPE_SALLE)->where('salle', 'Salle A')->exists())->toBeTrue()
        ->and(Reservation::where('type', Reservation::TYPE_JURY)->count())->toBe(2);

    $instance = $this->engine->apply($instance, 'envoyer_convocations', [], $this->agent);
    $instance = $this->engine->apply($instance, 'cloturer_soutenance', [], $this->president);
    $instance = $this->engine->apply($instance, 'editer_diplome', [], $this->agent);
    $instance = $this->engine->apply($instance, 'notifier_diplome', [], $this->agent);

    expect($instance->current_state)->toBe('diplome_disponible')
        ->and($instance->isCompleted())->toBeTrue();
});

test('workflow B : chevauchement de salle à la même plage horaire est bloqué', function () {
    $salle = 'Amphi 3';
    $debut = now()->addDays(20)->setTime(10, 0);
    $fin = $debut->copy()->addHours(2);
    $jury = User::factory()->create(['role' => UserRole::MembreCommission]);

    // Premier dossier planifié sur la plage.
    $d1 = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
    ]);
    app(ArchiveService::class)->archiverContenu($d1, 'rapport_approbation', 'Approbation', '%PDF-1.4', 'pdf', actor: $this->agent);
    $i1 = $this->engine->start(WorkflowDefinition::where('code', 'soutenance')->first(), $d1, actor: $this->doctorant);
    $i1 = $this->engine->apply($i1, 'valider_depot', ['inscriptions_count' => 3, 'credits_valides' => true], $this->agent);
    $i1 = $this->engine->apply($i1, 'designer_rapporteurs', [], $this->president);
    $i1 = $this->engine->apply($i1, 'enregistrer_rapports', ['rapporteurs_favorables' => 2], $this->agent);
    $i1 = $this->engine->apply($i1, 'designer_jury', [], $this->president);
    $i1 = $this->engine->apply($i1, 'envoyer_rectorat', [], $this->agent);
    app(ArchiveService::class)->archiverContenu($d1, 'arrete', 'Arrêté', '%PDF-1.4', 'pdf', actor: $this->admin);
    $i1 = $this->engine->apply($i1, 'valider_arrete', [], $this->admin);
    $i1 = $this->engine->apply($i1, 'planifier_soutenance', [
        'salle' => $salle,
        'soutenance_date' => $debut,
        'soutenance_fin' => $fin,
        'jury_membres' => [$jury->id],
    ], $this->gestionnaire);
    expect($i1->current_state)->toBe('planification');

    // Second dossier sur la même salle / plage → bloqué.
    $d2 = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
    ]);
    app(ArchiveService::class)->archiverContenu($d2, 'rapport_approbation', 'Approbation', '%PDF-1.4', 'pdf', actor: $this->agent);
    $i2 = $this->engine->start(WorkflowDefinition::where('code', 'soutenance')->first(), $d2, actor: $this->doctorant);
    $i2 = $this->engine->apply($i2, 'valider_depot', ['inscriptions_count' => 3, 'credits_valides' => true], $this->agent);
    $i2 = $this->engine->apply($i2, 'designer_rapporteurs', [], $this->president);
    $i2 = $this->engine->apply($i2, 'enregistrer_rapports', ['rapporteurs_favorables' => 2], $this->agent);
    $i2 = $this->engine->apply($i2, 'designer_jury', [], $this->president);
    $i2 = $this->engine->apply($i2, 'envoyer_rectorat', [], $this->agent);
    app(ArchiveService::class)->archiverContenu($d2, 'arrete', 'Arrêté', '%PDF-1.4', 'pdf', actor: $this->admin);
    $i2 = $this->engine->apply($i2, 'valider_arrete', [], $this->admin);

    expect(fn () => $this->engine->apply($i2, 'planifier_soutenance', [
        'salle' => $salle,
        'soutenance_date' => $debut,
        'soutenance_fin' => $fin,
        'jury_membres' => [$jury->id],
    ], $this->gestionnaire))
        ->toThrow(DomainException::class, 'réservée');

    // Même membre de jury, autre salle → toujours bloqué.
    $d3 = Dossier::factory()->create([
        'commission_id' => $this->commission->id,
        'doctorant_id' => $this->doctorant->id,
    ]);
    app(ArchiveService::class)->archiverContenu($d3, 'rapport_approbation', 'Approbation', '%PDF-1.4', 'pdf', actor: $this->agent);
    $i3 = $this->engine->start(WorkflowDefinition::where('code', 'soutenance')->first(), $d3, actor: $this->doctorant);
    $i3 = $this->engine->apply($i3, 'valider_depot', ['inscriptions_count' => 3, 'credits_valides' => true], $this->agent);
    $i3 = $this->engine->apply($i3, 'designer_rapporteurs', [], $this->president);
    $i3 = $this->engine->apply($i3, 'enregistrer_rapports', ['rapporteurs_favorables' => 2], $this->agent);
    $i3 = $this->engine->apply($i3, 'designer_jury', [], $this->president);
    $i3 = $this->engine->apply($i3, 'envoyer_rectorat', [], $this->agent);
    app(ArchiveService::class)->archiverContenu($d3, 'arrete', 'Arrêté', '%PDF-1.4', 'pdf', actor: $this->admin);
    $i3 = $this->engine->apply($i3, 'valider_arrete', [], $this->admin);

    expect(fn () => $this->engine->apply($i3, 'planifier_soutenance', [
        'salle' => 'Salle B',
        'soutenance_date' => $debut,
        'soutenance_fin' => $fin,
        'jury_membres' => [$jury->id],
    ], $this->gestionnaire))
        ->toThrow(DomainException::class, 'jury');
});

// ─── Workflow C : réclamations / ticketing ──────────────────────

test('workflow C : une réclamation suit le parcours jusqu’à la clôture', function () {
    $reclamation = Reclamation::factory()->create([
        'commission_id' => $this->commission->id,
        'user_id' => $this->doctorant->id,
        'type' => 'changement_de_titre',
        'urgence' => 'prioritaire',
    ]);

    $instance = $this->engine->start(
        WorkflowDefinition::where('code', 'reclamation')->first(),
        $reclamation,
        actor: $this->doctorant,
    );

    $instance = $this->engine->apply($instance, 'prendre_en_charge', [], $this->agent);
    expect($instance->current_state)->toBe('en_cours')
        ->and($reclamation->fresh()->statut)->toBe('en_cours');

    $instance = $this->engine->apply($instance, 'transmettre_direction', [], $this->agent);
    expect($instance->current_state)->toBe('en_attente_direction');

    $instance = $this->engine->apply($instance, 'valider_direction', [], $this->admin);
    expect($instance->current_state)->toBe('traitee');

    $instance = $this->engine->apply($instance, 'cloturer', [], $this->gestionnaire);
    expect($instance->current_state)->toBe('cloturee')
        ->and($instance->isCompleted())->toBeTrue()
        ->and($reclamation->fresh()->closed_at)->not->toBeNull();
});