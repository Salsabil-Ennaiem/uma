# PROMPT P1 — Étape 0 · Socle de la nouvelle application

## Cible du projet
**🟦 NOUVELLE APPLICATION** — à créer dans `C:\Users\salsa\Desktop\SA\pj\actuel\stageNashd\uma` (frère du dossier `voyager`). **Ne pas toucher au dossier `voyager` dans ce prompt.**

## Contexte (à lire d'abord, dans l'ordre)
1. `Cdc.md` — Lot 2, §1, §2 (espaces), §3 (architecture), §5 (« un seul espace back-office »)
2. `NOUVELLE_APP_GUIDE_INTEGRATION.md` — Étape 0 et §2 (décisions stratégiques), §4 (règles d'économie)
3. `STRATEGIE_ROADMAP_PLATEFORME_UMA.md` — objectif global, principes directeurs §3, registre §4

## Objectif
Créer le socle de la plateforme UMA : **Laravel 13 + Filament 5**, un **seul panneau admin** `/admin`, authentification Filament, **RBAC natif** fail-closed, structure de dossiers par domaines métier, et `config/uma.php` avec les flags limités aux 3 risques (PI, signature, RTL).

## Règles d'or (respecter, ne pas les discuter)
- **1 seul panneau admin** Filament (`/admin`). Pas d'installation des ressources dans un 2ᵉ panneau pour l'instant — le `/portal` usager est prévu mais **jamais** créé comme duplication de code : il utilisera les mêmes services/Policies.
- RBAC **natif** : enum `UserRole` + colonne `users.role` + `canAccessPanel()` **fail-closed** (rôle non déclaré → 403). **Interdiction d'installer `spatie/laravel-permission`.**
- Auth = Filament (login/reset/profil). **Pas de Breeze ni Jetstream.**
- Les outils bureautiques CSV maison (pas `maatwebsite/excel`) sauf si `.xlsx` explicitement exigé.
- Aucun commentaire superflu dans le code ; code en anglais, messages/README en français.

## Tâches (ordre strict)
1. `laravel new uma` puis `composer require filament/filament:"^5.0" -W` et `php artisan filament:install --panels`.
2. Ne conserver **qu'un seul panneau** `admin` (supprimer les panneaux supplémentaires créés par défaut).
3. Créer `app/Enums/UserRole.php` (ex. `admin`, `gestionnaire_ecole`, `president_commission`, `membre_commission`, `directeur_these`, `doctorant`, `agent_administration`) et la colonne `role` dans `users` (migration).
4. Implémenter `canAccessPanel()` **fail-closed** sur `User`, des Gates/Policies par domaine (au minimum 1 policy de référence commentant la matrice du CDC §2).
5. Créer la structure de dossiers par domaine : `app/Models`, `app/Services`, `app/PvRules/` (futurs contrats), `app/Filament/Resources/...` structurées par domaine (Commissions, Doctorat, Reunions...).
6. Créer `config/uma.php` avec **uniquement** les flags des 3 risques : `'compliance.signature_driver' => env('SIGNATURE_DRIVER', 'simple_image')`, `'compliance.pv_template_version' => 'v1'` — rien d'autre de spéculatif.
7. Créer un registre vide `ASSUMPTIONS.md` (modèle : `ID · hypothèse · mécanisme de bascule · décideur · date`).
8. Configurer le `.env.example` (DB, mail, storage public) et `.gitignore`.
9. Écrire les tests socle : page de login, accès `admin` par rôle, 403 pour rôle non déclaré.

## Critères d'acceptation
- `php -S localhost:2000 -t public/` + `php artisan test` → verts (tests socle).
- Un utilisateur sans rôle déclaré ne peut **jamais** accéder à `/admin` (403).
- `config/uma.php` ne contient **que** les flags des 3 risques (verrou anti-dérive).
- Aucun package RBAC externe dans `composer.json`.

## Output attendu
App fonctionnelle + assertion écrite pour chaque critère + compte rendu court des décisions prises (en particulier : nom du dossier, choix panneau unique, rôles initiaux).