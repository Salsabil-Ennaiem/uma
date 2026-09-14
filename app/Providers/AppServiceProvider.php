<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Invitation;
use App\Models\OdjTemplate;
use App\Models\Presence;
use App\Models\Reunion;
use App\Models\User;
use App\Policies\DecisionPolicy;
use App\Policies\DossierPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\OdjTemplatePolicy;
use App\Policies\PresencePolicy;
use App\Policies\PvPolicy;
use App\Policies\ReunionPolicy;
use App\PvSignatures\SignatureResolver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use SalsabilEnnaiem\PvModule\Models\Pv;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SignatureResolver::class);
    }

    public function boot(): void
    {
        $this->registerDomainGates();
        $this->registerPolicies();
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

    private function registerPolicies(): void
    {
        Gate::policy(Pv::class, PvPolicy::class);
        Gate::policy(Reunion::class, ReunionPolicy::class);
        Gate::policy(Invitation::class, InvitationPolicy::class);
        Gate::policy(Presence::class, PresencePolicy::class);
        Gate::policy(Dossier::class, DossierPolicy::class);
        Gate::policy(Decision::class, DecisionPolicy::class);
        Gate::policy(OdjTemplate::class, OdjTemplatePolicy::class);

        Gate::before(function (User $user) {
            return $user->role === UserRole::Admin ? true : null;
        });
    }
}
