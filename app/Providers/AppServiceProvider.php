<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\Decision;
use App\Models\Document;
use App\Models\Dossier;
use App\Models\Invitation;
use App\Models\OdjTemplate;
use App\Models\Presence;
use App\Models\RapportEtat;
use App\Models\Reunion;
use App\Models\User;
use App\Models\Universite;
use App\Models\Etablissement;
use App\Models\EcoleDoctorale;
use App\Policies\AuditLogPolicy;
use App\Policies\CommissionPolicy;
use App\Policies\DecisionPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\DossierPolicy;
use App\Policies\InstitutionPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\OdjTemplatePolicy;
use App\Policies\PresencePolicy;
use App\Policies\PvPolicy;
use App\Policies\RapportEtatPolicy;
use App\Policies\ReunionPolicy;
use App\Policies\UserPolicy;
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
        Gate::policy(Commission::class, CommissionPolicy::class);
        Gate::policy(Universite::class, InstitutionPolicy::class);
        Gate::policy(EcoleDoctorale::class, InstitutionPolicy::class);
        Gate::policy(Etablissement::class, InstitutionPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Invitation::class, InvitationPolicy::class);
        Gate::policy(Presence::class, PresencePolicy::class);
        Gate::policy(Dossier::class, DossierPolicy::class);
        Gate::policy(Decision::class, DecisionPolicy::class);
        Gate::policy(Document::class, DocumentPolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);
        Gate::policy(RapportEtat::class, RapportEtatPolicy::class);
        Gate::policy(OdjTemplate::class, OdjTemplatePolicy::class);

        Gate::before(function (User $user) {
            return $user->role === UserRole::Admin ? true : null;
        });
    }
}
