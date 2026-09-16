<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Plateforme UMA — socle (P1)

Application Laravel 13 + Filament 5 pour la plateforme de gestion de la formation doctorale de l'UMA (Lot 2 du CDC).

## Décisions prises (P1 Étape 0)

- **Dossier** : `C:\Users\salsa\Desktop\SA\pj\actuel\stageNashd\uma` (frère de `voyager`, dossier `voyager` non modifié).
- **Un seul panneau** : panneau unique Filament `/admin` (conformité CDC §5 « un seul espace back-office »). L'espace usager `/portal` prévu n'est pas dupliqué.
- **RBAC natif** : enum `App\Enums\UserRole` + colonne `users.role` + `canAccessPanel()` fail-closed + Gates/Policies par domaine. Aucun package RBAC externe.
- **Rôles initiaux** : `admin`, `gestionnaire_ecole`, `president_commission`, `membre_commission`, `directeur_these`, `doctorant`, `agent_administration`.
- **Risques** : `config/uma.php` ne déclare que les flags des 3 risques transversaux (signature, templates PV, verrou anti-dérive).

## Lancement

```bash
php -S localhost:2000 -t public/
php artisan test
```

## Étape 1 (P3) — Intégration de `pv-module`

Module de documents consommé **tel quel** via Composer (aucun copier-coller).

- **Dépôt** : `salsabil-ennaiem/pv-module` `^1.0` depuis **Packagist**, verrouillé en `v1.0.2` (dist GitHub). Publiable : `config/pv-module.php`, `lang/vendor/pv-module`.
- **Routes** : `/admin/documents/*` (noms gardés `pv-module.*`), middleware `['web', 'auth']`, `login` nommé → `/admin/login`.
- **`config/pv-module.php`** adapté : `user_model` = `App\Models\User`, `signature_mechanism` relié à `config('uma.compliance.signature_driver')`.
- **Évidences (demo locale)** : PV brouillon → en attente → validé ; PDF FR/AR dans `storage/app/evidence/` ; notifications base (cloche) + mails (log `storage/logs/laravel.log`).
- **Test d'intégration** : `tests/Feature/PvModuleIntegrationTest.php` (workflow complet + PDF FR/AR + notifications).

### Bugs package signalés (sans correction locale)

1. **Tags obsolètes** : `v1` et `v1.0.0` pointent sur l'ancien commit `3219e36` (avant R2/R3). **Résolu** par l'éditeur : les tags `v1.0.1` (`54a520b`, R2+R3) et `v1.0.2` (`dcd7af8`, fix routage) sont publiés et poussés ; la dépendance locale a été remplacée par Packagist.
2. **Tags de publication du prompt** : `pv-module-config` / `pv-module-lang` ne correspondent pas aux tags réels du package (`pv-config` / `pv-lang`).
3. **Noms de routes codés en dur** : les notifications appelaient `route('pv-module.show')` ; **résolu** par l'éditeur (`config('pv-module.routes.name_prefix', ...)`), livré dans `v1.0.2`. Le préfixe d'URL reste `admin/documents` et le préfixe de noms reste `pv-module.`.

## Étape 2 (P4) — Types CDC + templates FR/AR + écran « Modèles de décision »

Généralisation du moteur en « document engine du CDC » : les types sont déclarés **par configuration**, jamais en dur dans un `if/else`.

- **`config/pv-module.php` → `types`** : `pv`, `attestation`, `decision`, `arrete`, `invitation`, `diplome`, `fiche_acces` (7 types CDC).
- **Seeder `UmaDocumentTemplatesSeeder`** : 7 templates par défaut (sections header/contenu/signature, marges, orientation — `diplome` paysage, `invitation` marges 30). RTL AR confiné aux templates (`default_locale` / `rtl_locales`).
- **Écran admin « Modèles de décision »** (`/admin/decision-templates`) : CRUD Filament 5 sur `DecisionTemplate` — `label`, `description`, `email_subject`, `email_body` (variables `{prenom} {nom} {label}`), `commission_id`, `is_active` (RBAC natif `UserRole`).
- **Évidences** : `storage/app/private/evidence/p4-*.pdf` — attestation AR longue RTL (`dir="rtl"`, `lang="ar"`, PDF 44 Ko), invitation officielle (32 Ko), décision (32 Ko).
- **Tests** : `tests/Feature/CdcDocumentTypesTest.php` (13 tests — types en config, template par type, création + PDF par type, RTL AR long, écran Filament admin) ; suite complète : **28 tests / 130 assertions**.
- **Adaptation Filament 5** : `form(Schema $schema)` dans `Filament\Schemas\Schema` (plus `Filament\Forms\Form`) ; actions de table ≡ `Filament\Actions\EditAction|DeleteAction|BulkActionGroup|DeleteBulkAction` (le package `filament/tables` ne les définit plus) ; `$navigationIcon` et `$navigationGroup` typés `BackedEnum|null` / `UnitEnum|null`.
- **Capacité manquante détectée** : batch d'impression + logos/en-têtes paramétrables par structure → **sous-prompt package** rédigé (`Sous-prompt P4 — PdfService batch & logos`), à exécuter après validation de P4.

## Étape 3 (P5) — RBAC métier branchée sur les contrats du package

Cible : `🟦 NOUVELLE APPLICATION`. Les contrats du package sont **implémentés ici**, jamais modifiés dans le package.

- **3 implémentations de contrats** dans `app/PvRules/` (liées via `config/pv-module.php`) :
  - `PvRules` (`CanManagePv`) : matrice rôles UMA — création `admin|gestionnaire_ecole|president_commission|agent_administration` ; validation/signature par les présents ; gestion restreinte au **périmètre commission** (`source_type=commission`) ; fail-closed + log discret.
  - `ApprovalRules` : seuils paramétrables `config('uma.approval')` — `unanimous` (défaut) ou `quorum %` ; la validation du créateur approuve (règle Voyager).
  - `ParticipantResolver` : signataires résolus depuis les **entités métier** (membres + président d'une `Commission`, jury) ; normalisation héritée du package.
- **Signature R2** (`app/PvSignatures/`) : `SignatureResolver` unique lit `uma.compliance.signature_driver` ; `SimpleImageSignatureStrategy` (délègue au package) et `QualifiedSignatureStrategy` (stub journalise « certificat requis », basculable par `SIGNATURE_ALLOW_WITHOUT_CERTIFICATE`). Retour toujours mécanisme + horodatage. `SIGNATURE_DRIVER=qualified` change le comportement **sans toucher une ligne de logique** (testé).
- **Hiérarchie métier** (app seulement) : `Universite → EcoleDoctorale → Etablissement → Commission` (+ pivot `commission_user`), exposée dans l'admin (groupe « Institution », ressources Filament 5 `Universites/EcoleDoctorales/Etablissements/Commissions`).
- **Policies** qui délèguent au contrat : `PvPolicy` (tout délègue à `CanManagePv`), `CommissionPolicy`, `InstitutionPolicy`, `DecisionTemplatePolicy` (anti-IDOR : président limité à sa commission) + `Gate::before` admin.
- **Tests** : `tests/Feature/P5RbAcContratsTest.php` (10 tests — liens contrats, RBAC fail-closed par rôle, 403 sans garde, bascule driver `qualified`, trace mécanisme+horodatage, seuils quorum/unanime, resolution commission, hiérarchie, anti-IDOR commission/décision/PV). Suite complète : **38 tests / 178 assertions**. `composer.json` toujours sans `spatie/laravel-permission`.

## Étape 4 (P6) — Module Réunions de commission en Filament

Cible : **🟦 NOUVELLE APPLICATION** — module réécrit en Filament (référence Voyager en lecture seule), branché sur le package pour le PV signé.

- **Modèles** (`app/Models/`) : `Reunion` (statuts `brouillon → planifiee → en_cours → terminee/annulee`, type `presentiel/visio/hybride`, ODJ, soft-deletes, `estPassee()`, `pvs()` = source `reunion/{id}`), `Invitation`, `Presence` (présent/absent/excusé), `Dossier` (+pivot `reunion_dossier` positionné), `Decision` (snapshot du modèle paramétrable), `OdjTemplate`, `AuditLog` (append-only : `action`, avant/après, IP). Enums `ReunionStatut` (matrice de transitions + `label()`), `ReunionType`, `InvitationStatut`, `PresenceStatut`, `DossierStatut`.
- **Migration** `2026_09_16_000001_create_reunions_module_tables.php` (+ 6 factories).
- **Services** : `ReunionService` (création en transaction + **auto-ajout des membres** hors président, `transition()` avec garde d'état + audit + notifications, `genererPv()` → `PvService::store(..., 'reunion', id)` puis `send` aux présents), `DecisionService` (record snapshot + dossier → `traite`, `exportCsv`, `pvContenu`).
- **Ressources Filament** (générées CLI `--generate`, personnalisées ensuite) : `Reunions/ReunionResource` + `Schemas/ReunionForm` + `Tables/ReunionsTable` (badges statut, filtres statut/commission, `TrashedFilter` corbeille), `Dossiers/*`, `Decisions/*` (colonne décideur). Pages custom : `ManageReunionPresences`, `ManageReunionDecisions` (RBAC en `mount`), `ReunionCorbeille` (restore/force delete, admin), pages statut + « Générer le PV » dans `EditReunion`, export décisions CSV.
- **Policies RBAC** : admin tout ; président de SA commission ; gestionnaire école ; agent administratif (création, présence, décisions) ; membre vue seulement ; doctorant vue de sa propre décision ; fail-closed ; `genererPv` = réunion passée + contrat package `CanManagePv`.
- **Notifications** : `ReunionPlanifiee`/`ReunionTerminee` (base) + `Mail/ReunionConvocation` + vue markdown `emails/reunion/convocation.blade.php` (canal mail unique).
- **Tests** : `tests/Feature/ReunionsModuleTest.php` (9 tests — parcours complet création→convocation→présences→décisions→terminer→PV, transitions invalides, RBAC par rôle, corbeille, mallette décision, anti-IDOR commission, smoke Filament 200/403). Suite complète : **47 tests / 239 assertions**.

## Conformité & décisions UMA (P10 — clôture)

État au **2026-09-15** : UMA injoignable — hypothèses en **défaut sûr**, planifiées pour validation.
Registre consolidé app + package : `../voyager/DECISIONS_UMA.md` · dossier de réunion :
`../voyager/docs/DOSSIER_RE_SOLLICITATION_UMA.md`.

| Risque | Défaut sûr appliqué | Décision UMA à acter |
|---|---|---|
| **R1 — PI** | namespace `SalsabilEnnaiem` + cession par contrat | nom/licence/dépôt de livraison (bloquant livraison) |
| **R2 — Signature** | `simple_image` + trace (mécanisme + horodatage) | niveau de conformité (simple vs qualifiée eIDAS) |
| **R3 — RTL** | templates FR/AR confinés aux vues (`dir="rtl"`) | conformité des modèles officiels UMA |
| **Workflow** | définitions/seuils paramétrés en base | validation définitions A/B/C + seuils JORT |

Mise à jour avec date + responsable à la clôture (chaque ligne : `validée` / `amendée` / `à corriger`).

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
