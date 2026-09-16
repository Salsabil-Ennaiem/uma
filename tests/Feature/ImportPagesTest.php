<?php

use App\Enums\UserRole;
use App\Filament\Pages\Imports\ImportEnseignants;
use App\Filament\Pages\Imports\ImportTheses;
use App\Models\Dossier;
use App\Models\User;
use Livewire\Livewire;

test('page import enseignants accessible admin, interdite aux autres rôles', function () {
    Livewire::actingAs(User::factory()->create(['role' => UserRole::Admin]))
        ->test(ImportEnseignants::class)
        ->assertOk();

    Livewire::actingAs(User::factory()->create(['role' => UserRole::MembreCommission]))
        ->test(ImportEnseignants::class)
        ->assertForbidden();

    Livewire::actingAs(User::factory()->create(['role' => UserRole::Doctorant]))
        ->test(ImportEnseignants::class)
        ->assertForbidden();
});

test('page import thèses accessible admin/agent, interdite aux autres', function () {
    Livewire::actingAs(User::factory()->create(['role' => UserRole::Admin]))
        ->test(ImportTheses::class)
        ->assertOk();

    Livewire::actingAs(User::factory()->create(['role' => UserRole::AgentAdministration]))
        ->test(ImportTheses::class)
        ->assertOk();

    Livewire::actingAs(User::factory()->create(['role' => UserRole::DirecteurThese]))
        ->test(ImportTheses::class)
        ->assertForbidden();
});