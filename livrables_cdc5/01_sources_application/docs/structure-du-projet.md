# Structure du projet — Plateforme UMA (arborescence et rôle de chaque dossier/fichier)

> Document de référence pour comprendre **ce que fait chaque dossier et chaque fichier**.
> Lecteurs visés : nouveau développeur, auditeur du stage, revue de P8/P9.
> Généré au moment de la clôture P8 ; à mettre à jour au fil de l'eau (comme `docs/commands-used.md` et `docs/bugs-and-solutions.md`).

---

## Vue d'ensemble

```
uma/
├── app/                  ← Code applicatif Laravel (RBAC, Réunions, Archivage, Workflows, Filament)
├── bootstrap/            ← Amorçage framework (app, providers, cache)
├── config/               ← Configuration (dont pv-module, archive, workflow, uma)
├── database/             ← Migrations, seeders, factories
├── docs/                 ← Documentation d'étape (bugs, commandes, structure)
├── lang/                 ← Traductions (dont celles publiées depuis pv-module)
├── public/               ← Point d'entrée web + assets publiés (Filament, build)
├── resources/            ← Vues Blade, CSS/JS, emails markdown
├── routes/               ← routes/web.php + console.php
├── storage/              ← Logs, cache, disques (documents archivés, évidences)
├── tests/                ← Suites de tests par étape (P1 → P8)
├── vendor/               ← Packages Composer (dont salsabil-ennaiem/pv-module)
└── …fichiers racine (voir §9)
```

---

## 1. `app/` — code applicatif

### `app/Console/Commands/`
| Fichier | Rôle |
| --- | --- |
| `DemoP6SliceVerticale.php` | Commande `php artisan uma:demo-p6-slice` : déroule la slice verticale P6 (dépôt → ODJ → présences → décisions → PV signé → attestation) et écrit les évidences PDF dans `storage/app/private/evidence/`. Idempotente. |

### `app/Contracts/`
| Fichier | Rôle |
| --- | --- |
| `SignatureStrategy.php` | Contrat commun des stratégies de signature (P5) : le `SignatureResolver` y délègue sans connaître l'implémentation. |

### `app/Enums/`
Énumérations `string` du domaine, utilisées dans les modèles, migrations et écrans Filament.

| Fichier | Valeurs notables |
| --- | --- |
| `DossierStatut.php` | EnAttente / EnCours / Valide / Rejete … (statut métier du dossier) |
| `InvitationStatut.php` | EnAttente / Acceptee / Refusee / Absent … |
| `PresenceStatut.php` | Present / Absent / Retard / Excuse … |
| `RapportEtatType.php` | Type de rapport/état (etat, rapport…) |
| `ReunionStatut.php` | Brouillon / Planifiee / EnCours / Terminee / Annulee + transitions autorisées |
| `ReunionType.php` | Presentiel / Hybride / Visio |
| `UserRole.php` | Admin / GestionnaireEcole / PresidentCommission / MembreCommission / DirecteurThese / AgentAdministration / Doctorant |

### `app/Filament/Resources/` — écrans d'administration
Chaque ressource suit le conventionnement Filament 5 : `<Entité>Resource.php` + sous-dossiers `Pages/`, `Schemas/`, `Tables/`, `RelationManagers/`.

| Dossier ressource | Domaine (étape) | Pages principales |
| --- | --- | --- |
| `AuditLogs/` | Journal d'audit, lecture seule (P7) | `ListAuditLogs` |
| `Commissions/` | Commissions de discipline | `ManageCommissions` (CRUD simple) |
| `Decisions/` | Décisions de réunion (P6) | List / Create / Edit |
| `DecisionTemplateResource/` | Modèles de décision FR/AR (P4) | List / Create / Edit |
| `Documents/` | Mallette du dossier (« Documents » du pv-module exige un slug différent) (P7) | List / Create / Edit + `DocumentVersionsRelationManager` |
| `Dossiers/` | Dossiers de doctorants (P6) | List / Create / Edit + relation managers Documents & Decisions |
| `EcoleDoctorales/`, `Etablissements/`, `Universites/` | Hiérarchie institutionnelle | `Manage*` (CRUD simples) |
| `RapportEtats/` | États/rapports HTML+PDF (P7) | List / Create / Edit |
| `Reunions/` | Module Réunions, cœur de l'app (P6) | List / Create / Edit / `ManageReunionPresences` / `ManageReunionDecisions` / `ReunionCorbeille` |

> Les sous-dossiers `Schemas/` et `Tables/` séparent les définitions de formulaire et de table du reste de la ressource (pattern maison, voir `DossierForm.php`, `ReunionsTable.php`…).

### `app/Http/Controllers/`
| Fichier | Rôle |
| --- | --- |
| `Controller.php` | Base avec `AuthorizesRequests` + `ValidatesRequests` (bug P7 n°19). |
| `RapportController.php` | Routes web `admin/rapports-etats/{etat}/apercu` + `/pdf`, gardées par `authorize('generer')`. |

### `app/Mail/` — Mailable (canal mail dédié)
| Fichier | Rôle |
| --- | --- |
| `ReunionConvocation.php` | Convocation de réunion (P6) — seul canal mail des invités (bug P6 n°10). |
| `WorkflowStateChanged.php` | Notification mail « état du workflow changé » (P8), vue markdown `emails/workflow/state-changed`. |

### `app/Models/` — modèles Eloquent
| Modèle | Rôle |
| --- | --- |
| `AuditLog` | Journal d'audit **append-only** (P7) : `log()` statique, update/delete/save refusés. |
| `Commission` | Commission de discipline (rattachée à un établissement, président). |
| `Decision`, `DecisionTemplate` | Décision de réunion + modèle de décision (P4/P6). |
| `Document`, `DocumentVersion` | Mallette polymorphique : pièces + versions **immuables** (hash, chemin, rétention) (P7). |
| `Dossier` | Dossier de doctorant (sujet des workflows A/B). Relations : doctorant, commission, réunions, décisions, documents, `workflowInstances`. |
| `EcoleDoctorale`, `Etablissement`, `Universite` | Hiérarchie institutionnelle. |
| `Invitation`, `Presence` | Invitations + présence (P6). |
| `OdjTemplate` | Modèle d'ordre du jour (P6). |
| `RapportEtat` | Définition d'un état/rapport (marges, orientation, en-tête) généré par le package (P7). |
| `Reclamation`, `ReclamationDiscussion` | Réclamation/ticket + discussions autour d'une réclamation (Workflow C, P8). |
| `Reservation` | Réservation de salle / membre de jury — contrôle anti-chevauchement (`scopeChevauche`) (P8). |
| `Reunion` | Réunion (statuts, ODJ, membres, lien visio) (P6). |
| `User` | Utilisateur avec `role` (cast `UserRole`). |
| `WorkflowAuditTrail`, `WorkflowDefinition`, `WorkflowGuard`, `WorkflowInstance`, `WorkflowTransition` | Moteur de workflow paramétrable en base (P8) : définitions, instances, transitions, gardes, traces. |

### `app/Notifications/` — notifications (canal database / mail)
| Fichier | Rôle |
| --- | --- |
| `ReunionPlanifiee`, `ReunionTerminee` | Notifications de cycle de réunion (P6) — `via()` = `database` uniquement (le mail passe par le Mailable). |
| `WorkflowTransitioned` | Notification « transition exécutée » (P8), canal `database`. |

### `app/Policies/` — autorisations métier (contrôlées par les contrats, P5)
| Rôle de chaque policy | Exemple |
| --- | --- |
| Délègue AUX contrats RBAC | `PvPolicy`, `ReunionPolicy`, `DecisionPolicy`, `DossierPolicy`, `InstitutionPolicy`, `InvitationPolicy`, `PresencePolicy`, `OdjTemplatePolicy`, `DocumentPolicy`, `RapportEtatPolicy` |
| Logique locale mineure | `CommissionPolicy`, `DecisionTemplatePolicy`, `AuditLogPolicy` (lecture admin, écriture refusée), `UserPolicy` |

### `app/Providers/`
| Fichier | Rôle |
| --- | --- |
| `AppServiceProvider.php` | Bindings de base (contrats → implémentations, signatures…). |
| `Filament/AdminPanelProvider.php` | Déclare le panneau `/admin` : ressources découvertes automatiquement, RBAC, couleurs, middlewares. |

### `app/PvRules/` — RBAC métier branchée sur les contrats du package (P5)
| Fichier | Rôle |
| --- | --- |
| `PvRules.php` | Implémente `CanManagePv` (fail-closed par rôle) : `canCreate/canUpdate/canSend/canValidate/canSign/canDelete/canDownload/canManageTemplates`. |
| `ApprovalRules.php` | Règles d'approbation (quorum/unanime) via bascule `uma.approval.mode`. |
| `ParticipantResolver.php` | Résolution des participants d'une réunion (membres auto). |

### `app/PvSignatures/` — stratégies de signature (P5)
| Fichier | Rôle |
| --- | --- |
| `SignatureResolver.php` | Délègue selon `SIGNATURE_DRIVER` (qualified / simple-image). |
| `QualifiedSignatureStrategy.php` | Signature « qualifiée » (mode production). |
| `SimpleImageSignatureStrategy.php` | Signature par image (mode recette/tests). |

### `app/Services/` — services métier (la logique vit ici, pas dans Filament)
| Service | Étape | Rôle |
| --- | --- | --- |
| `ArchiveService.php` | P7 | `archiverContenu`, `archiverUpload`, `nouvelleVersionContenu`, rétention par type. |
| `AuditLogger.php` | P7 | Écrit les lignes append-only dans `audit_logs`. |
| `DecisionService.php` | P6 | Génère/exporte les décisions (`exportCsv`…). |
| `NotificationService.php` | P6 | Envoie invitations / notifications de réunion (mail + base). |
| `RapportService.php` | P7 | `generer`/`genererEnMasse`/`apercuHtml` d'un état → PDF via le package, archivé dans la mallette. |
| `ReunionService.php` | P6 | Cycle de vie réunion (statuts, PV, participants). |
| `WorkflowEngine.php` | P8 | Moteur : `start()`, `availableTransitions()`, `can()`, `apply()`, `guard()` — **transitions pilotées par la base**. |
| `WorkflowGuardResolver.php` | P8 | Évalue les gardes (`data`, `document`, `contract`, `no_overlap`). |
| `WorkflowActions.php` | P8 | Actions de bord déclarées (`attestation.generer`, `reservation.creer`, `reclamation.actualiser`, `notifyUser`). |

---

## 2. `bootstrap/`
| Fichier/Dossier | Rôle |
| --- | --- |
| `app.php` | Construction de l'application Laravel. |
| `providers.php` | Liste des providers enregistrés. |
| `cache/packages.php`, `cache/services.php` | Cache des packages/services (régénéré par Composer). |

---

## 3. `config/`
Configuration « classique » Laravel + configurations métier :

| Fichier | Rôle |
| --- | --- |
| `app.php`, `auth.php`, `cache.php`, `database.php`, `filesystems.php`, `logging.php`, `mail.php`, `queue.php`, `services.php`, `session.php` | Config framework standard. |
| `pv-module.php` | Config **publiée depuis le package** (route prefix…). |
| `archive.php` | Disque d'archivage, types de pièces, rétention par type (P7). |
| `uma.php` | Config plateforme (mode d'approbation : quorum/unanime…). |
| `workflow.php` | Constantes workflows P8 : types/priorités de réclamation (paramétrables), liste des rôles. |

---

## 4. `database/`
### `database/migrations/`
| Migration | Étape | Contenu |
| --- | --- | --- |
| `0001_01_01_000000_create_users_table.php` | socle | Table `users`. |
| `0001_01_01_000001_create_cache_table.php` | socle | Cache. |
| `0001_01_01_000002_create_jobs_table.php` | socle | Files de jobs. |
| `2026_09_13_000001_add_role_to_users_table.php` | P1 | Colonne `role` (enum). |
| `2026_09_13_000002_create_notifications_table.php` | P1 | Notifications (canal database). |
| `2026_09_14_000001_create_decision_templates_table.php` | P4 | Modèles de décision. |
| `2026_09_15_000001_create_institution_hierarchy_tables.php` | P3 | Universités → écoles → établissements → commissions. |
| `2026_09_16_000001_create_reunions_module_tables.php` | P6 | Module Réunions (réunions, dossiers, invitations, présences, décisions, ODJ). |
| `2026_09_20_000001_create_archivage_rapports_tables.php` | P7 | Archivage + audit (documents, document_versions, rapport_etats, audit_logs). |
| `2026_09_21_000001_create_workflow_engine_tables.php` | P8 | Moteur workflow (definitions, transitions, guards, instances, audit_trails) + réclamations + réservations. |

### `database/factories/`
Une factory par modèle principal (`UserFactory`, `DossierFactory`, `ReunionFactory`, `InvitationFactory`, `PresenceFactory`, `DecisionFactory`, `DecisionTemplateFactory`, `OdjTemplateFactory`, fact. hiérarchie `Universite/EcoleDoctorale/Etablissement/Commission`, `ReclamationFactory`, `ReservationFactory`, `WorkflowDefinitionFactory`).

### `database/seeders/`
| Seeder | Étape | Rôle |
| --- | --- | --- |
| `DatabaseSeeder.php` | socle | Point d'entrée des seeds. |
| `UmaDocumentTemplatesSeeder.php` | P4 | 7 types de documents CDC + templates par défaut. |
| `WorkflowSeeder.php` | P8 | Déclarations **en base** des Workflows A (inscription), B (soutenance) et C (réclamation) : états, transitions, rôles, gardes, actions, notifications. Idempotent. |

---

## 5. `docs/`
| Fichier | Rôle |
| --- | --- |
| `bugs-and-solutions.md` | Registre des **vrais bugs/obstacles** par étape (cause racine + solution + leçon). P1→P7 documentés. |
| `commands-used.md` | Commandes **exactes** utilisées par étape (tests ciblés, migrate, make:filament-*, pint…). |
| `structure-du-projet.md` | Ce document. |

---

## 6. `public/` & `resources/`
| Dossier | Rôle |
| --- | --- |
| `public/index.php` | Front controller. |
| `public/build/` | Assets compilés (Vite). |
| `public/css/filament/`, `public/js/filament/`, `public/fonts/filament/` | Assets **publiés** par Filament. |
| `resources/views/` | Vues Blade : `welcome.blade.php`, `emails/reunion/convocation`, `emails/workflow/state-changed`, pages custom Filament Réunions (`filament/resources/reunions/pages/*`). |
| `resources/css/`, `resources/js/` | Entrées Vite de l'app. |

---

## 7. `routes/`
| Fichier | Rôle |
| --- | --- |
| `web.php` | Routes web hors-Filament (aperçu/PDF rapports gardés par policy). |
| `console.php` | Commandes artisan (dont programmes planifiés). |

---

## 8. `tests/`
| Suit | Fichier | Couverture |
| --- | --- | --- |
| P1 | `SocleCriteriaTest.php`, `AdminPanelAccessTest.php` | Socle, accès `/admin`. |
| P3 | `PvModuleIntegrationTest.php` | Intégration package (création → envoi → signature → PDF FR/AR). |
| P4 | `CdcDocumentTypesTest.php` | Types CDC, templates, PDF par type, RTL AR. |
| P5 | `P5RbAcContratsTest.php` | Contrats, RBAC fail-closed, driver qualified, seuils, anti-IDOR. |
| P6 | `ReunionsModuleTest.php` | Module Réunions (9 tests). |
| P7 | `P7ArchivageAuditTest.php` | Archivage, audit append-only, rapports/états, batch 50, routes web. |
| P8 | `P8WorkflowEngineTest.php` | Moteur : parcours A/B/C complets, transition déclarée sans code, chevauchement jury/salles bloqué. |
| — | `ExampleTest.php` (Feature/Unit) | Tests de démonstration Laravel (neutres). |
| — | `Pest.php`, `TestCase.php` | Configuration Pest / TestCase. |

---

## 9. Fichiers racine
### Documentation / prompt de l'éditeur
| Fichier | Rôle |
| --- | --- |
| `Cdc.md` | Cahier des charges complet (procédures, JORT, workflows A/B/C, §1.10 ticketing, §10 chevauchement). |
| `STRATEGIE_ROADMAP_PLATEFORME_UMA.md` | Stratégie + roadmap P1→P10, principes (contrats, vant de l'interface tôt). |
| `NOUVELLE_APP_GUIDE_INTEGRATION.md` | Guide d'intégration de la nouvelle app avec le pv-module. |
| `P8_workflow_engine_base.md` | **Prompt de l'étape P8** (tâches, règles d'or, critères d'acceptation). |
| `P9_recette_deploiement.md` | Prompt de l'étape P9 (recette + déploiement). |
| `PV_MODULE_CDC_COVERAGE.md` | Correspondance CDC ↔ capacités du package. |
| `WORKFLOW_ET_COMPTES.md` | Diagrammes d'états + matrice des rôles par workflow. |
| `ASSUMPTIONS.md` | Registre des hypothèses (template : ID · hypothèse · bascule · décideur · date) — **à remplir au fil des décisions**. |
| `README.md` | Présentation du projet. |

### Configuration / outillage
| Fichier | Rôle |
| --- | --- |
| `AGENTS.md` / `CLAUDE.md` | Guidelines pour les agents (Boost, conventions). |
| `boost.json` | Configuration **Laravel Boost** (agents cibles, skill activés, cloud/MCP). |
| `.mcp.json` | Serveurs MCP (ici `laravel-boost` → `php artisan boost:mcp`). |
| `composer.json` / `composer.lock` | Dépendances PHP (dont `salsabil-ennaiem/pv-module`, `filament/filament`, `laravel/boost`). |
| `package.json` / `package-lock.json` / `vite.config.js` / `.npmrc` | Dépendances JS + build Vite. |
| `.env` / `.env.example` | Configuration locale (ne pas versionner `.env`). |
| `phpunit.xml` | Config des tests. |
| `artisan` | CLI Laravel. |
| `.gitattributes`, `.gitignore`, `.editorconfig` | Normalisation Git/éditeur. |

---

## 10. Fichiers `.gitkeep` — à quoi servent-ils ?
`app/Filament/Resources/{Reunions, Doctorat, Commissions}/.gitkeep`, `app/PvRules/.gitkeep`, `app/Services/.gitkeep`.

**Rôle** : Git ne versionne pas les **dossiers vides**. Un `.gitkeep` (fichier vide placé au moment où le dossier a été créé pour une étape future) oblige Git à garder le dossier dans le dépôt, même avant son premier fichier réel.

**États actuels** :
- `app/Services/` et `app/PvRules/` → **dossiers désormais remplis**, leur `.gitkeep` est un **vestige inoffensif** (peut être supprimé).
- `app/Filament/Resources/Doctorat/.gitkeep` → le dossier **reste vide** : c'est le seul où le `.gitkeep` a encore un **rôle fonctionnel** (prévu pour une future ressource « Doctorat »).

| Fichier | Utile encore ? |
| --- | --- |
| `app/Services/.gitkeep` | Non (dossier rempli en P7/P8) — supprimable. |
| `app/PvRules/.gitkeep` | Non (dossier rempli en P5). |
| `app/Filament/Resources/Reunions/.gitkeep` | Non (dossier rempli en P6). |
| `app/Filament/Resources/Commissions/.gitkeep` | Non (dossier rempli en P3/P6). |
| `app/Filament/Resources/Doctorat/.gitkeep` | **Oui** (dossier encore vide — à garder). |

---

## 11. Réponse courte — « l'architecture est-elle un développement par module interopérable ? »
Voir le dialogue associé. En résumé : **ce n'est PAS une architecture « modules packagés interopérables » au sens SOA/plugins**, c'est un **monolithe Laravel fortement modulaire** :

- **1 seul artefact déployable** : l'app `uma`. Les « modules » (Réunions, Archivage, Workflows, RBAC) sont des **dossiers cohérents du monolithe** (`app/Services`, `app/Filament/Resources/…`), pas des paquets indépendants réutilisables ailleurs.
- **Interopérabilité réelle et revendiquée** : au niveau du **package `pv-module`** (Composer, depuis Packagist) et de ses **contrats** (`CanManagePv`, `ApprovalRules`, `ParticipantResolver`, `SignatureStrategy`). C'est là que vit le vrai couplage « par contrat → implémentation interchangeable » (divers signatures, bascule quorum/unanime).
- Le moteur de workflow P8 étend cette logique : **transitions/gardes/actions paramétrées en base** → l'ajout d'un workflow ne nécessite plus de code ; c'est une forme de « module » par données, mais toujours à l'intérieur du même monolithe.

**Conclusion** : architecture = **monolithe modulaire + un package externe interopérable** (point d'extension par contrat). Pas d'indépendance de déploiement/réutilisation entre les modules internes — le qualificatif « interopérable » est vrai surtout pour `pv-module`, pas pour les modules de l'app.