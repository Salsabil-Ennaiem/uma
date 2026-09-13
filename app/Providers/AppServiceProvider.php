<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerDomainGates();
    }

    private function registerDomainGates(): void
    {
        Gate::define('manage-users', fn (User $user) => in_array($user->role, [UserRole::Admin], true));

        Gate::define(
            'manage-commissions',
            fn (User $user) => in_array($user->role, [UserRole::Admin, UserRole::PresidentCommission, UserRole::MembreCommission], true),
        );

        Gate::define(
            'manage-reunions',
            fn (User $user) => in_array($user->role, [UserRole::Admin, UserRole::PresidentCommission, UserRole::MembreCommission, UserRole::AgentAdministration], true),
        );

        Gate::define(
            'manage-doctorat',
            fn (User $user) => in_array($user->role, [UserRole::Admin, UserRole::GestionnaireEcole, UserRole::PresidentCommission, UserRole::DirecteurThese, UserRole::Doctorant], true),
        );
    }
}
