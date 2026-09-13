<?php

use App\Enums\UserRole;
use App\Models\User;

test('config/uma.php ne déclare que les flags des 3 risques', function () {
    $flags = config('uma.compliance');

    expect(array_keys($flags))->toBe(['signature_driver', 'pv_template_version'])
        ->and($flags['signature_driver'])->toBe('simple_image')
        ->and($flags['pv_template_version'])->toBe('v1');
});

test('aucun package RBAC externe dans composer.json', function () {
    $composer = json_decode(file_get_contents(base_path('composer.json')), true);
    $packages = [...array_keys($composer['require']), ...array_keys($composer['require-dev'])];

    expect($packages)->not->toContain('spatie/laravel-permission');
});

test('un seul panneau Filament admin existe', function () {
    $providers = glob(app_path('Providers/Filament/*.php'));

    expect($providers)->toHaveCount(1);
});

test('UserRole enum déclare les rôles initiaux du CDC §2', function () {
    expect(UserRole::cases())->toHaveCount(7);
});

test('les Gates de domaine respectent la matrice CDC §2 (fail-closed)', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $member = User::factory()->create(['role' => UserRole::MembreCommission]);
    $undeclared = User::factory()->create();

    expect($admin->can('manage-users'))->toBeTrue();
    expect($member->can('manage-commissions'))->toBeTrue();
    expect($member->can('manage-users'))->toBeFalse();
    expect($undeclared->can('manage-commissions'))->toBeFalse();
    expect($undeclared->can('manage-reunions'))->toBeFalse();
});
