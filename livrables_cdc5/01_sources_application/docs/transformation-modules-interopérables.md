# Transformation vers des modules interopérables — Analyse & feuille de route

> **Document de référence** : est-ce faisable, quel effort, et par où commencer ?
> Répond à la question : « Si on veut rendre notre monolithe un développement par module interopérable, quelles étapes ? »
> Rédigé à la fin de P8 (sept. 2026).

---

## Table des matières
1. [État actuel — diagnostic](#1-état-actuel--diagnostic)
2. [Définition — « module interopérable » dans notre contexte](#2-définition--module-interopérable-dans-notre-contexte)
3. [Leçon du package pv-module — preuve que ça marche](#3-leçon-du-package-pv-module--preuve-que-ça-marche)
4. [Feuille de route — les 7 étapes](#4-feuille-de-route--les-7-étapes)
5. [Priorisation & effort estimé](#5-priorisation--effort-estimé)
6. [Risques & contre-indications](#6-risques--contre-indications)

---

## 1. État actuel — diagnostic

Notre code est actuellement un **monolithe Laravel modulaire** :

```
uma/
├── app/
│   ├── Services/       ← 8 services métier
│   ├── Filament/       ← 15 ressources Filament
│   ├── Models/         ← 18 modèles Eloquent
│   ├── Policies/       ← 15 policies
│   ├── Contracts/      ← 1 contrat (SignatureStrategy)
│   └── PvRules/        ← 3 implémentations de contrats du package
├── config/             ← 6 configs métier
├── database/           ← 10 migrations, 15 factories, 3 seeders
└── tests/              ← 8 suites (P1→P8, 72 tests)
```

**Ce qui fonctionne déjà comme un module** :
- Le package `pv-module` est **vraiment interopérable** (séparé sur Packagist, consommé par contrat).
- Les contrats (`CanManagePv`, `ApprovalRules`, `SignatureStrategy`) **permettent déjà de changer d'implémentation** sans modifier le code métier.
- Le moteur P8 paramétré en base est **une forme de modularisation par données** (ajouter un workflow ≠ ajouter du code).

**Ce qui reste du monolithe** :
- Les 8 services métier sont **couplés entre eux** par des `app()` ou des imports directs.
- Les ressources Filament dépendent directement des modèles de l'app (pas d'interface隔離).
- Les migrations sont **monolithiques** (1 seul fichier par étape, pas de migrations par module).
- La base de données est **partagée** (pas de schéma par module).
- Les tests testent **l'ensemble** (pas de tests isolés par module).

---

## 2. Définition — « module interopérable » dans notre contexte

Un **module interopérable** est :

| Critère | Signification concrète UMA |
|---------|---------------------------|
| **Autonome** | Le module peut être développé/testé **sans** les autres modules (pas de dépendances transversales). |
| **Interface claire** | Le module expose des **contrats** (interfaces PHP) — pas des classes concrètes. |
| **Package** | Le module est un **package Composer** (installable/désinstallable indépendamment). |
| **Isolé en base** | Le module possède ses **propres tables/migrations** (pas de JOIN inter-modules). |
| **Remplaçable** | On peut substituer l'implémentation d'un module par une autre (ex. : remplacer `Réunions` par un module externe). |
| **Remplaçable par un package externe** | Un tiers peut écrire un module équivalent pour UMA. |

> **NB** : on ne vise PAS une architecture microservices (un déploiement par module). On vise des **modules internes** au même déploiement, séparés par des **contrats**.

---

## 3. Leçon du package pv-module — preuve que ça marche

Le package `pv-module` **fonctionne déjà** comme un module interopérable :

```
┌─────────────────────────────────────────────────────────┐
│ uma (monolithe)                                        │
│                                                         │
│  ┌─────────────┐      contrats        ┌──────────────┐  │
│  │  app/        │ ──────────────────► │  pv-module   │  │
│  │  PvRules/    │   CanManagePv       │  (Packagist) │  │
│  │  Approval/   │   ApprovalRules     │              │  │
│  │  Resolver/   │   ParticipantResolver│              │  │
│  └─────────────┘                      └──────────────┘  │
│                                                         │
│  Le package NE CONNAÎT PAS les classes de l'app.        │
│  L'app NE MODIFIE PAS le package.                       │
│  Le couplage se fait UNIQUEMENT par les contrats.       │
└─────────────────────────────────────────────────────────┘
```

**Ce que ça prouve** : la separation par contrats est **techniquement faisable** dans un projet Laravel/Packagist. La question est : peut-on l'appliquer à d'autres modules de l'app ?

---

## 4. Feuille de route — les 7 étapes

### Étape 1 : Identifier les futurs modules (inventory)

Déterminer **quels modules** seront extraits. Critères de sélection :

| Module candidat | Priorité | Pourquoi | Effort estimé |
|----------------|----------|----------|---------------|
| `pv-module` | **✅ DÉJÀ FAIT** | Package existant sur Packagist | — |
| **Réunions** (P6) | Haute | Module le plus complet (réunions, présences, décisions, ODJ, PV) ; forte valeur de réutilisation. | Élevé (2-3 semaines) |
| **Archivage/audit** (P7) | Moyenne | Module transversal (documents, versions immuables, audit trail) ; réutilisable par d'autres apps. | Moyen (1-2 semaines) |
| **Workflow** (P8) | Moyenne | Moteur générique déjà paramétré en base ; très réutilisable. | Moyen (1 semaine) |
| **Hiérarchie institutionnelle** | Basse | Universités/Écoles/Établissements/Commissions est spécifique à UMA ; pas de réutilisation externe. | Faible (3 jours) |
| **Notifications/Email** | Basse | Notifications de réunion/workflow ; peu de logique métier. | Faible (2 jours) |

> **Recommandation** : commencer par **Réunions** (module le plus complet et le plus demandé en réutilisation).

---

### Étape 2 : Extraire les contrats (interfaces PHP)

Pour chaque module, créer un **package d'interfaces** qui définit les capacités du module sans imposer l'implémentation.

**Exemple concret — module Réunions** :

```php
// src/Contracts/ReunionServiceInterface.php
namespace Uma\Contracts;

use App\Models\Reunion;
use App\Models\User;

interface ReunionServiceInterface
{
    public function planifier(Reunion $reunion, User $actor): Reunion;
    public function demarrer(Reunion $reunion, User $actor): Reunion;
    public function terminer(Reunion $reunion, User $actor): Reunion;
    public function annuler(Reunion $reunion, User $actor): Reunion;
    public function genererPv(Reunion $reunion, User $actor, ?string $contenu = null): object;
    public function exportCsv(Reunion $reunion): string;
}
```

```php
// src/Contracts/NotificationServiceInterface.php
namespace Uma\Contracts;

use App\Models\Reunion;
use App\Models\User;

interface NotificationServiceInterface
{
    public function sendInvitations(Reunion $reunion, array $participantIds): void;
    public function sendReunionPlanifiee(Reunion $reunion): void;
    public function sendReunionTerminee(Reunion $reunion): void;
}
```

> **Règle d'or** : un module **consomme** les contrats mais **ne connaît pas** les classes concrètes des autres modules.

---

### Étape 3 : Séparer la base de données par module

Chaque module doit avoir ses **propres migrations** et **propres tables**, sans JOIN inter-modules.

**Avant (monolithe)** :
```
database/migrations/2026_09_16_000001_create_reunions_module_tables.php
  → crée reunions, invitations, presences, decisions, reunion_dossier, etc.
```

**Après (modules)** :
```
packages/uma-reunions/database/migrations/
  001_create_reunions_table.php
  002_create_invitations_table.php
  003_create_presences_table.php
  004_create_decisions_table.php

packages/uma-archivage/database/migrations/
  001_create_documents_table.php
  002_create_document_versions_table.php
  003_create_audit_logs_table.php

packages/uma-workflow/database/migrations/
  001_create_workflow_definitions_table.php
  002_create_workflow_transitions_table.php
  ...
```

**Règle** : pas de `FOREIGN KEY` inter-modules (les relations inter-modules sont gérées par des IDs métier, pas par des contraintes DB).

---

### Étape 4 : Créer les packages Composer

Pour chaque module, créer un package Composer dans `packages/` :

```
packages/
├── uma-reunions/          ← Module Réunions
│   ├── composer.json      ← "name": "uma/reunions-module"
│   ├── src/
│   │   ├── Contracts/     ← Interfaces
│   │   ├── Models/        ← Modèles Eloquent (Reunion, Invitation, Presence, Decision)
│   │   ├── Services/      ← Implémentations (ReunionService, NotificationService)
│   │   ├── Policies/      ← Autorisations
│   │   ├── Filament/      ← Ressources Filament (optionnel, avec garde RBAC)
│   │   └── Providers/     ← ServiceProvider (register, boot, migrations, config)
│   ├── database/
│   │   └── migrations/    ← Migrations propres au module
│   ├── config/
│   │   └── reunions.php   ← Configuration du module
│   └── tests/             ← Tests unitaires/intégration du module
│
├── uma-archivage/         ← Module Archivage/audit
├── uma-workflow/          ← Module Moteur de workflow
├── uma-hierarchie/        ← Module Hiérarchie institutionnelle
└── uma-notifications/     ← Module Notifications
```

**`composer.json` du module** :
```json
{
    "name": "uma/reunions-module",
    "autoload": {
        "psr-4": {
            "Uma\\Reunions\\": "src/"
        },
        "psr-4": {
            "Uma\\Reunions\\Database\\Migrations\\": "database/migrations/"
        }
    },
    "require": {
        "php": "^8.2",
        "illuminate/database": "^11.0",
        "illuminate/support": "^11.0"
    },
    "extra": {
        "laravel": {
            "providers": [
                "Uma\\Reunions\\Providers\\ReunionsServiceProvider"
            ]
        }
    }
}
```

---

### Étape 5 : Brancher les packages à l'application principale

Dans `composer.json` de l'app `uma` :

```json
{
    "require": {
        "uma/reunions-module": "@dev",
        "uma-archivage-module": "@dev",
        "uma-workflow-module": "@dev"
    },
    "repositories": [
        {
            "type": "path",
            "url": "packages/*"
        }
    ]
}
```

Ou en mode `symlink` pour le dev :

```bash
composer config repositories.uma '{"type": "path", "url": "packages/*"}'
composer require uma/reunions-module:@dev --prefer-dist
```

**Le service provider du module** enregistre automatiquement :
- Les migrations (via `$this->loadMigrationsFrom()`)
- La config (via `$this->mergeConfigFrom()`)
- Les vues Blade (via `$this->loadViewsFrom()`)
- Les routes (via `$this->loadRoutesFrom()`)

---

### Étape 6 : Refactorer les tests (isolation par module)

**Avant** :
```
tests/Feature/
  P6ReunionsModuleTest.php    ← test l'app complète
  P7ArchivageAuditTest.php    ← test l'app complète
```

**Après** :
```
packages/uma-reunions/tests/
  Unit/ReunionServiceTest.php
  Feature/ReunionWorkflowTest.php

packages/uma-archivage/tests/
  Unit/ArchiveServiceTest.php
  Feature/DocumentVersionTest.php

packages/uma-workflow/tests/
  Feature/WorkflowEngineTest.php

tests/Feature/
  IntegrationTest.php    ← test l'intégration inter-modules (si nécessaire)
```

**Règle** : chaque module a ses propres tests, exécutables **indépendamment**.

---

### Étape 7 : Créer le « module racine » (app UMA)

L'app `uma` devient un **module racine** qui assemble les modules :

```
uma/
├── packages/                  ← Modules extraits (interne)
├── app/
│   ├── Console/               ← Commandes transverses
│   ├── Filament/              ← Ressources transverses (Workflow admin, Audit)
│   ├── Http/Controllers/      ← Routes web transverses
│   ├── Models/                ← Modèles transverses (User, Commission, etc.)
│   ├── Policies/              ← Policies transverses
│   ├── Providers/             ← Service providers (assemble les modules)
│   └── Services/              ← Services transverses (AuditLogger)
├── config/                    ← Configs des modules (chargées par chaque module)
├── database/                  ← Migrations transverses + seeders
└── tests/                     ← Tests d'intégration
```

**Le `AppServiceProvider` de l'app** :
```php
public function register(): void
{
    // Bind les contrats → implémentations (fail-closed)
    $this->app->bind(
        \Uma\Contracts\ReunionServiceInterface::class,
        \Uma\Reunions\Services\ReunionService::class
    );
    $this->app->bind(
        \Uma\Contracts\SignatureStrategy::class,
        fn () => match(config('uma.signature_driver')) {
            'qualified' => app(\Uma\PvSignatures\QualifiedSignatureStrategy::class),
            default => app(\Uma\PvSignatures\SimpleImageSignatureStrategy::class),
        }
    );
}
```

---

## 5. Priorisation & effort estimé

### Plan d'action recommandé

```
P9 (Recette) — ne pas extraire les modules maintenant
  → Focus : tests IDOR, imports CSV, déploiement CCK.
  → Les modules restent dans le monolithe pour P9.

P10 (Post-déploiement) — extraction des contrats
  → Créer les packages d'interfaces (uma-contracts).
  → Extraire le moteur Workflow (le plus simple, déjà paramétré en base).
  → Extraire Archivage/audit (module transversal clair).

P11 (Post-déploiement) — extraction des modules métier
  → Extraire Réunions (module le plus complet).
  → Extraire Hiérarchie institutionnelle (optionnel).
  → Tests d'intégration inter-modules.
```

### Effort total estimé

| Étape | Effort | Prérequis |
|-------|--------|-----------|
| Inventory des modules | 1 jour | — |
| Extraction des contrats | 2-3 jours | Inventory |
| Séparation DB par module | 3-5 jours | Contrats |
| Création des packages Composer | 5-7 jours | DB séparée |
| Branchement à l'app | 2-3 jours | Packages créés |
| Refactoring des tests | 3-5 jours | Branchement |
| Module racine + intégration | 3-5 jours | Tests refactorés |
| **Total estimé** | **19-29 jours** | — |

> **Estimation réaliste** : 4 à 6 semaines de travail continu (hors P9/recette).

---

## 6. Risques & contre-indications

### Pourquoi **ne PAS** extraire les modules maintenant ?

| Risque | Impact | Mitigation |
|--------|--------|------------|
| **Complexité d'intégration inter-modules** | Les modules ont des dépendances croisées (Réunions → Dossiers → Commissions → Users). | Extraire les contrats **avant** les implémentations. |
| **Performance** | Plus de résolution de container, plus d'abstracts, plus d'interfaces. | Mesurer avant/après avec `php artisan benchmark`. |
| **Debug plus difficile** | Les erreurs passent par des couches d'abstraction. | Garder des logs détaillés dans chaque module. |
| **Effort vs bénéfice** | Pour un projet universitaire (UMA), la réutilisation externe est **peu probable**. | Extraire les modules **après** la recette, pas avant. |
| **Couplage Filament** | Les ressources Filament dépendent des modèles ; les extraire en package nécessite de séparer Filament des modèles. | Créer un « module Filament » séparé, ou garder Filament dans l'app racine. |

### Quand extraire les modules ?

**Après la recette (P9)** — pas avant. Les raisons :
1. Le monolithe est **plus simple à recetter** (un seul déploiement, une seule base).
2. Les tests d'intégration sont **plus fiables** en monolithe.
3. Le déploiement CCK est **plus simple** avec un seul artefact.
4. L'extraction de modules est un **projet d'amélioration continue**, pas un pré-requis au déploiement.

---

## 7. Conclusion — réponse à la question

> « Si on veut rendre notre monolithe un développement par module interopérable, quelles étapes ? »

| Phase | Action | Quand |
|-------|--------|-------|
| **Aujourd'hui (P8)** | On a **prouvé que ça marche** avec `pv-module` (package externe interopérable). Le moteur P8 est **une forme avancée de modularisation** (paramétrage en base). | Terminé |
| **P9 (recette)** | On reste en monolithe. On recette, on déploie. On documente les hypothèses (ASSUMPTIONS.md). | En cours |
| **P10 (post-déploiement)** | On crée le package `uma-contracts` (interfaces). On extrait les modules un par un (Workflow → Archivage → Réunions). | Futur |
| **P11+** | On publie les modules sur Packagist (si d'autres universités veulent réutiliser UMA). | Très futur |

**La réponse courte** : oui, c'est faisable, mais c'est un projet de **4-6 semaines** à réaliser **après la recette**, pas avant. Le monolithe actuel est un choix délibéré et validé par le CDC — il ne faut pas le fragiliser avant le déploiement.
