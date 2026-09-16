# PV Module — Origine du code & gestion des langues

## 1. D'où vient le code du module ?

Le module `salsabil-ennaiem/pv-module` n'a **pas** été écrit ligne par ligne en partant de zéro.
Il a été **extrait des fonctionnalités PV qui existaient déjà dans l'application** (voyager / ERP Réunion),
puis **généralisé** sous forme de paquet Composer réutilisable.

### Ce qui a été déplacé depuis l'app (copie + adaptation)

| Dossier du paquet | Origine dans l'app | Adaptation |
|---|---|---|
| `src/Models/` (`Pv`, `PvSignature`, `PvTemplate`, `PvValidation`) | `app/Models/` | namespace `SalsabilEnnaiem\PvModule\`, casts figés |
| `src/Http/Controllers/` (`PvController`, `SignatureController`, `TemplateController`) | `app/Http/Controllers/` | routes nommées `pv-module.*`, vues préfixées par namespace |
| `src/Http/Requests/` (`StorePvRequest`, `UpdatePvRequest`) | `app/Http/Requests/` | validation centralisée |
| `resources/views/` (`pv/`, `signature/`, `templates/`, `partials/`, `layouts/`, `pdfs/`) | `resources/views/` | namespace de vues `pv-module::` |
| `database/migrations/` | `database/migrations/` | mêmes schémas SQLite-compatibles |
| `src/Notifications/` | `app/Notifications/` | canaux email + database |
| `src/Seeders/DefaultPvTemplateSeeder.php` | seeder interne | re-sémé par l'hôte |
| `config/pv-module.php` | ancienne config disséminée | **centralisée** : `user_model`, `routes_prefix`, `storage_disk`, `types`, contrats |

### Ce qui a été créé pour rendre le paquet générique

- `src/Contracts/` : `CanManagePv`, `ApprovalRules`, `ParticipantResolver` — **l'hôte implémente ces
  interfaces** pour brancher sa RBAC, son périmètre et son domaine sans toucher au code du module.
- `src/Defaults/` : implémentations par défaut (`DefaultPvRules`, `DefaultApprovalRules`,
  `DefaultParticipantResolver`) utilisées tant que l'hôte n'a pas publié et configuré ses propres classes.
- `src/Services/` : `PvService` (workflow : brouillon → envoi → versioning → validation),
  `PdfService` (rendu mPDF), `SignatureService`.
- `src/PvModuleServiceProvider.php` : auto-discovery, `mergeConfigFrom`, `loadMigrationsFrom`,
  `loadViewsFrom`, `loadJsonTranslationsFrom`, publishes (`pv-module-config | pv-module-lang |
  pv-module-migrations | pv-module-views | pv-module-assets`).
- `composer.json` : `name: salsabil-ennaiem/pv-module`, autoload PSR-4 `SalsabilEnnaiem\PvModule\ → src/`, `extra.laravel.providers`.

> Points d'accroche hôte : `config/pv-module.php` (publié), migrations (auto-chargées par le provider),
> langues (package + publishables), thème/vues (`pv-module::*` publiables en `resources/views/vendor/pv-module`).

---

## 2. Ajouter une nouvelle langue au module (ex : `es`)

### 2.1 Dans le paquet (publié sur GitHub/Packagist)

1. Prendre le fichier de référence `resources/lang/fr.json` (le fichier **source des clés** — toutes les
   langues doivent partager **exactement les mêmes clés**).
2. Le copier en `resources/lang/es.json`.
3. Traduire **uniquement les valeurs** ; ne jamais renommer/supprimer une clé.
4. Valider la parité des clés :

```bash
# depuis la racine du repo (ici voyager)
php -r "$f=json_decode(file_get_contents('packages/salsabil-ennaiem/pv-module/resources/lang/fr.json'),true); $n=json_decode(file_get_contents('packages/salsabil-ennaiem/pv-module/resources/lang/es.json'),true); echo count(array_diff(array_keys($f),array_keys($n)));"
```

→ doit retourner `0`. Encoder en **UTF-8 sans BOM**.

### 2.2 Dans l'app hôte

1. Republier les langues si besoin : `php artisan vendor:publish --tag=pv-module-lang --force`
   → crée/maj `lang/vendor/pv-module/es.json`.
2. Activer `es` :
   - `APP_LOCALE=es` dans `.env`, **ou** `config/app.php → 'locale' => env('APP_LOCALE', 'fr')`,
   - et si l'app a un sélecteur de langue (comme le middleware `SetLocale` de voyager), ajouter
     `'es'` à la liste autorisée :
     `in_array($request->lang, ['ar', 'en', 'fr'])` → `['ar', 'en', 'fr', 'es']`.
3. Purger le cache : `php artisan view:clear` (vues compilées) puis `php artisan config:clear`.

> Si le module est consommé via un repo VCS (pas encore publé), commit + `composer update salsabil-ennaiem/pv-module`
> dans l'app hôte avant le point 2.2.

---

## 3. Dépannage — problèmes fréquents

| Symptôme | Cause probable | Solution |
|---|---|---|
| Les textes restent en français alors que locale=es | `en/…/es.json` absent ou non chargé | Republier `pv-module-lang --force`, vérifier `APP_LOCALE`, `config:clear` + `view:clear` |
| Une clé s'affiche brute (ex : « Mes PV ») au lieu de la traduction | Clé absente du fichier de la langue courante | Ajouter la clé (fr = référentiel), rejouer la vérification de parité |
| L'UI bascule en anglais sur une clé manquante | `app.fallback_locale` vaut `en` | Mettre `APP_FALLBACK_LOCALE=fr`, ou ajouter la clé |
| `ConvertFrom-Json`/JSON: duplicate key error | Deux clés proches (`Contenu & décisions` vs `Contenu & Décisions`) | Normal — vérifier la parité avec `php`, pas PowerShell |
| Fichier JSON avec BOM (invisible) | Éditeur Windows | Resauvegarder en UTF-8 sans BOM |
| La langue n'apparaît pas dans le sélecteur de l'app hôte | Middleware hôte (ex `SetLocale`) n'autorise pas la langue | Ajouter le code langue à la liste `in_array(...)` |
| Erreur `Could not find a version of package salsabil-ennaiem/pv-module matching your minimum-stability` | Paquet sans tag stable | Utiliser `composer require salsabil-ennaiem/pv-module:dev-main` ou `"*@dev"` (cf. guide §2.3) |
| Après ajout d'une langue, les tests d'assertions FR échouent | L'app tournait en locale `en` avec retour « clé brute » (français par accident) | Mettre `APP_LOCALE=fr` (+ `APP_FALLBACK_LOCALE=fr`) dans `.env` et `phpunit.xml` ; `config/app.php` en `env('APP_LOCALE','fr')` |

---

## 4. Fichiers touchés pour activer le français (fait le 07/09/2026)

- `config/app.php` : `'locale' => 'fr'` → `'locale' => env('APP_LOCALE', 'fr')`
- `.env` : ajout `APP_LOCALE=fr`, `APP_FALLBACK_LOCALE=fr`
- `phpunit.xml` : ajout `<env name="APP_LOCALE" value="fr"/>`
- `tests/Feature/ExampleTest.php` : test pré-existant cassé (`/` redirige vers Filament, 200 jamais renvoyé) — corrigé pour asserter la redirection. *Sans rapport avec la langue.*
- Ajout `resources/lang/en.json` (209 clés) + complétion des 4 clés manquantes dans `resources/lang/ar.json`.

**Changement requis pour une langue supplémentaire : uniquement le fichier `resources/lang/<code>.json`**
(+ les fichiers hôte `.env`/`phpunit.xml`/middleware si besoin). Aucune modification de code/blade.