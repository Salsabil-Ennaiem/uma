# Guide de renommage - `uma/pv-module` → `salsabil-ennaiem/pv-module`

> Récapitulatif exact de l'opération réalisée : renommer le package, son dossier, son namespace, et vérifier qu'aucune erreur ne subsiste côté code, fichiers et terminal.

## 1. Contexte

| Avant | Après |
| --- | --- |
| Dossier : `packages/uma/pv-module` | `packages/salsabil-ennaiem/pv-module` |
| Nom Composer : `uma/pv-module` | `salsabil-ennaiem/pv-module` |
| Namespace : `Uma\PvModule` | `SalsabilEnnaiem\PvModule` |
| Provider : `Uma\PvModule\PvModuleServiceProvider` | `SalsabilEnnaiem\PvModule\PvModuleServiceProvider` |

## 2. Renommage du dossier

```
packages/uma/pv-module  →  packages/salsabil-ennaiem/pv-module
```

Puis suppression du dossier parent résiduel vide : `packages/uma`.

## 3. Dans le package (`packages/salsabil-ennaiem/pv-module`)

### 3.1 `composer.json`
- `"name": "uma/pv-module"` → `"salsabil-ennaiem/pv-module"`
- `"autoload": { "psr-4": { "Uma\\PvModule\\": "src/" } }` → `"SalsabilEnnaiem\\PvModule\\": "src/"`
- `"extra": { "laravel": { "providers": ["Uma\\PvModule\\PvModuleServiceProvider"] } }` → `SalsabilEnnaiem\PvModule\PvModuleServiceProvider`
- `"authors"` : Salsabil Ennaiem + email auteur

### 3.2 Remplacement du namespace dans tout le code
Script PowerShell appliqué à tous les fichiers `.php` et `.blade.php` du package (src, config, routes, database, resources, tests) :

```powershell
$root = "C:\Users\salsa\Desktop\SA\pj\actuel\stageNashd\voyager\packages\salsabil-ennaiem\pv-module"
Get-ChildItem -Path $root -Recurse -File -Include *.php,*.blade.php |
  ForEach-Object {
    $c = [System.IO.File]::ReadAllText($_.FullName)
    $n = $c.Replace('Uma\PvModule','SalsabilEnnaiem\PvModule')
           .Replace('uma/pv-module','salsabil-ennaiem/pv-module')
    if ($n -ne $c) {
      [System.IO.File]::WriteAllText($_.FullName, $n, (New-Object System.Text.UTF8Encoding($false)))
    }
  }
```

NB : `ReadAllText`/`WriteAllText` en UTF-8 sans BOM = les accents sont préservés (vérifié à la lecture, ne pas se fier à l'affichage console PowerShell qui déforme `é`).

### 3.3 Ajustements manuels
- `tests/Feature/PvWorkflowTest.php` : l'assertion de classe corrigée vers `SalsabilEnnaiem\PvModule`.
- `LICENSE` : `Copyright (c) 2026 uma` → `Copyright (c) 2026 Salsabil Ennaiem`.
- `README.md` : titre + `composer require` + exemples `->class="SalsabilEnnaiem\PvModule\..."` + contrat.

## 4. Dans l'hôte (projet Voyager)

- `composer.json` :
  - `"require": { "uma/pv-module": "*@dev" }` → `"salsabil-ennaiem/pv-module": "*@dev"`
  - `"repositories": [{ "type": "path", "url": "packages/uma/pv-module" }]` → `"url": "packages/salsabil-ennaiem/pv-module"`
- Régénération du lock : `composer update salsabil-ennaiem/pv-module`
- `tests/Feature/PvModuleUiTest.php` et `tests/Feature/PvWorkflowTest.php` : `use` + références → `SalsabilEnnaiem\PvModule` (même script PS).
- Docs markdown (`docs/PV_MODULE_INTEGRATION_GUIDE.md`, `PV_MODULE_ORIGINE_ET_MULTILANGUE.md`, `PV_MODULE_CDC_COVERAGE.md`, `PV_MODULE_DEV_JOURNAL.md`, `PV_MODULE_PLAN.md`) : même remplacement.

### Fichiers NE PAS modifier
- `storage/framework/views/*.php` : vues compilées (régénérées seules).
- `bootstrap/cache/*.php` : cache régénéré à la volée.
- `database/database.sqlite` : données de test, pas du code.
- `.phpunit.result.cache` : cache de test, ignoré par git.
- `resources/views/layouts/app.blade.php:574` : faux positif « uma » (texte `diffForHumans`), ne pas toucher.

## 5. Dans l'app de test (consommateur)

`C:\Users\salsa\AppData\Local\Temp\opencode\test-app\composer.json` :
- `"require": { "uma/pv-module": "*@dev" }` → `"salsabil-ennaiem/pv-module": "*@dev"`
- `"autoload-dev": { "Uma\\PvModule\\Tests\\": ".../packages/uma/pv-module/tests" }` → `"SalsabilEnnaiem\\PvModule\\Tests\\": ".../packages/salsabil-ennaiem/pv-module/tests"`
- `"repositories": [{ "type": "path", "url": "...\\packages\\uma\\pv-module" }]` → `...\packages\salsabil-ennaiem\pv-module`
- Puis : `composer update salsabil-ennaiem/pv-module`

### Précautions JSON
Dans un `composer.json`, un backslash se note `\\` (validation JSON). Rustine d'édition : le texte du fichier contient donc littéralement `\\` — une recherche/remplacement en texte brut doit reproduire exactement ces doubles backslashes.

## 6. Vérifications (terminal)

Depuis le test-app (Laravel 13, phpunit installé) :

```powershell
php vendor\bin\phpunit --bootstrap vendor/autoload.php `
  --configuration "C:\Users\salsa\Desktop\SA\pj\actuel\stageNashd\voyager\packages\salsabil-ennaiem\pv-module\phpunit.xml"
```

Résultat attendu : **OK (12 tests, 51 assertions)**.

Depuis le package :

```powershell
composer validate --strict        # -> ./composer.json is valid
Get-ChildItem src,config,routes,database -Recurse -File -Include *.php `
  | ForEach-Object { php -l $_.FullName }   # -> No syntax errors detected, 0 erreur
```

Recherche de résidus (0 → propre) :

```powershell
Select-String -Path <cibles> -Pattern 'uma/pv-module|Uma\PvModule'
```

## 7. Prochaines étapes (publication)

1. `git init` **dans** `packages/salsabil-ennaiem/pv-module` (le dossier porteur du `composer.json`).
2. Premier commit (+ tag `v1.0.0`).
3. Repo GitHub `Salsabil-Ennaiem/pv-module`, push + push des tags.
4. Déclaration Packagist (associe le nom `salsabil-ennaiem/pv-module` à ce repo).