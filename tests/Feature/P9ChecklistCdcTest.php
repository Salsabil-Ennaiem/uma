<?php

use App\Enums\UserRole;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Mail\FilteredListEmail;
use App\Models\Commission;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Universite;
use App\Models\User;
use App\Services\CsvExportService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function () {
    Mail::fake();

    $this->universite = Universite::factory()->create();
    $this->ecole = EcoleDoctorale::factory()->create(['universite_id' => $this->universite->id]);
    $this->etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $this->ecole->id]);
    $this->commissionA = Commission::factory()->create(['etablissement_id' => $this->etablissement->id]);
    $this->commissionB = Commission::factory()->create(['etablissement_id' => $this->etablissement->id]);

    $this->admin = User::factory()->create(['role' => UserRole::Admin, 'name' => 'Amine Administrateur', 'email' => 'amine@admin.tn']);
    $this->doctorantA = User::factory()->create(['role' => UserRole::Doctorant, 'name' => 'Sami Ben Ali', 'email' => 'sami.benali@uma.tn']);
    $this->doctorantB = User::factory()->create(['role' => UserRole::Doctorant, 'name' => 'Rim Trabelsi', 'email' => 'rim.trabelsi@uma.tn']);
    $this->directeur = User::factory()->create(['role' => UserRole::DirecteurThese, 'name' => 'Pr Leila Hamdi', 'email' => 'leila.hamdi@umg.tn']);

    $this->commissionA->membres()->attach([
        $this->doctorantA->id,
        $this->directeur->id,
    ]);
});

// ─── §6.1 Recherche multicritères (OR multi-colonnes) ─────────────

test('§6 : la recherche multi-colonnes filtre par nom OU email', function () {
    Livewire::actingAs($this->admin)
        ->test(ListUsers::class)
        ->searchTable('Trabelsi')
        ->assertCanSeeTableRecords([$this->doctorantB])
        ->assertCanNotSeeTableRecords([$this->doctorantA]);

    Livewire::actingAs($this->admin)
        ->test(ListUsers::class)
        ->searchTable('sami.benali@uma.tn')
        ->assertCanSeeTableRecords([$this->doctorantA])
        ->assertCanNotSeeTableRecords([$this->doctorantB, $this->directeur]);
});

// ─── §6.2 Filtres combinés ET sur plusieurs colonnes ──────────────

test('§6 : les filtres se combinent en ET sur plusieurs colonnes', function () {
    Livewire::actingAs($this->admin)
        ->test(ListUsers::class)
        ->filterTable('role', 'doctorant')
        ->filterTable('commission_id', $this->commissionA->id)
        ->assertCanSeeTableRecords([$this->doctorantA])
        ->assertCanNotSeeTableRecords([$this->doctorantB, $this->directeur, $this->admin]);

    // Filtre seule sur le rôle sans contrainte de commission.
    Livewire::actingAs($this->admin)
        ->test(ListUsers::class)
        ->filterTable('role', 'directeur_these')
        ->assertCanSeeTableRecords([$this->directeur])
        ->assertCanNotSeeTableRecords([$this->doctorantA, $this->doctorantB]);
});

test('§6 : la recherche et les filtres sont appliqués aussi sur les dossiers', function () {
    $dossierA = Dossier::factory()->create([
        'commission_id' => $this->commissionA->id,
        'doctorant_id' => $this->doctorantA->id,
        'objet' => 'Thèse IA — agents conversationnels',
    ]);
    $dossierB = Dossier::factory()->create([
        'commission_id' => $this->commissionB->id,
        'doctorant_id' => $this->doctorantB->id,
        'objet' => 'Thèse histoire médiévale',
    ]);

    Livewire::actingAs($this->admin)
        ->test(\App\Filament\Resources\Dossiers\Pages\ListDossiers::class)
        ->searchTable('agents conversationnels')
        ->assertCanSeeTableRecords([$dossierA])
        ->assertCanNotSeeTableRecords([$dossierB]);
});

// ─── §6.3 Colonnes personnalisables (affichage / masquage) ────────

test('§6 : chaque colonne est personnalisable (toggleable)', function () {
    $livewire = Livewire::actingAs($this->admin)->test(ListUsers::class);

    $columns = collect($livewire->instance()->getTable()->getColumns())
        ->keyBy(fn ($column) => $column->getName());

    expect($columns->get('name')->isToggleable())->toBeTrue()
        ->and($columns->get('email')->isToggleable())->toBeTrue()
        ->and($columns->get('role')->isToggleable())->toBeTrue()
        ->and($columns->get('commissions_count')->isToggleable())->toBeTrue()
        ->and($columns->get('created_at')->isToggleable())->toBeTrue()
        ->and($columns->get('created_at')->isToggledHiddenByDefault())->toBeTrue();
});

// ─── §6.4 Export du résultat filtré ───────────────────────────────

test('§6 : export CSV maison du résultat', function () {
    $records = collect([$this->doctorantA, $this->doctorantB]);

    $csv = CsvExportService::generate($records, [
        'Nom' => 'name',
        'Email' => 'email',
        'Rôle' => 'role',
    ]);

    $lines = str_getcsv($csv, "\n");

    expect($lines[0])->toBe('Nom;Email;Rôle')
        ->and(implode("\n", $lines))->toContain('Sami Ben Ali')
        ->and(implode("\n", $lines))->toContain('rim.trabelsi@uma.tn')
        ->and(implode("\n", $lines))->toContain('doctorant');
});

test('§6 : export natif Filament configuré (UserExporter)', function () {
    expect(UserExporter::getModel())->toBe(User::class);

    $columns = UserExporter::getColumns();

    expect(collect($columns)->map(fn ($c) => $c->getName())->all())
        ->toBe(['name', 'email', 'role', 'commissions_count', 'created_at']);
});

// ─── §6.5 Envoi d'email à une liste filtrée ───────────────────────

test('§6 : envoi d\'un email personnalisé à la sélection filtrée (tags résolus)', function () {
    Livewire::actingAs($this->admin)
        ->test(ListUsers::class)
        ->callTableBulkAction('sendEmail', [$this->doctorantA, $this->doctorantB], [
            'subject' => 'Rappel de dossier',
            'body' => "Bonjour {{name}},\n\nVotre dossier est en attente.\n{{role}}",
        ]);

    Mail::assertQueued(FilteredListEmail::class, 2);

    Mail::assertQueued(FilteredListEmail::class, function (FilteredListEmail $mail): bool {
        return str_contains($mail->subjectLine, 'Rappel de dossier')
            && str_contains($mail->bodyHtml, 'Bonjour Sami Ben Ali')
            && str_contains($mail->bodyHtml, 'Doctorant');
    });
});

// ─── §6 RBAC : pas de contournement de Policy ─────────────────────

test('§6 : la liste des utilisateurs est réservée aux admins (fail-closed)', function () {
    $membre = User::factory()->create(['role' => UserRole::MembreCommission]);
    $docteur = User::factory()->create(['role' => UserRole::Doctorant]);

    $this->actingAs($this->admin)->get('/admin/users')->assertOk();

    $this->actingAs($membre)->get('/admin/users')->assertForbidden();
    $this->actingAs($docteur)->get('/admin/users')->assertForbidden();

    expect(Gate::forUser($membre)->allows('viewAny', User::class))->toBeFalse();
});