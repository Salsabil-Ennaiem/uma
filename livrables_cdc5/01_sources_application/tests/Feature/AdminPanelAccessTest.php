<?php

use App\Enums\UserRole;
use App\Models\User;

test('le formulaire de connexion du panneau admin est accessible', function () {
    $this->get('/admin/login')->assertOk();
});

test('un invité est redirigé vers la connexion', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('un utilisateur avec un rôle déclaré accède au panneau admin', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('un utilisateur sans rôle déclaré ne peut jamais accéder au panneau admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('un gestionnaire école accède également au panneau admin', function () {
    $user = User::factory()->create(['role' => UserRole::GestionnaireEcole]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});
