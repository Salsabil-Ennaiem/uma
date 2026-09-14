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
