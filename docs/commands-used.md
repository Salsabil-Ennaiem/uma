# Commandes utilisées — Plateforme UMA (par étape : P1, P3, P4, P5, P6, P7, P8)

> La CLI Filament est **la source des ressources** (`make:filament-resource --generate`, `make:filament-page`) : on génère, puis on personnalise les fichiers produits.
> **⚠️ Provenance** : les commandes P1/P3/P4/P5 proviennent de **sessions antérieures** — listées ici **reconstituées d'après le README et les évidences** (pas une trace verbatim). Les commandes **P6/P7/P8** sont celles des **sessions actuelles** (exactes pour les invocations vérifiées).

---

## P1 — Socle (nouvelle application + panneau `/admin` + RBAC natif)

| Commande | Objectif |
| --- | --- |
| `composer create-project laravel/laravel uma` | Créer l'application Laravel (fait en amont de la session). |
| `composer require laravel/boost --dev` | Installer Laravel Boost (parametricitation des guidelines AGENTS). |
| `php artisan boost:install` | Installer/stabiliser Boost dans le projet. |
| `composer require filament/filament:^5 -W` | Installer Filament 5. |
| `php artisan filament:install --panels` | Créer le(s) panneau(x) Filament (`/admin`). |
| `php artisan make:filament-user` | Créer l'utilisateur Filament initial. |
| `php artisan migrate` | Appliquer les migrations du socle (dont `users.role`). |

---

## P3 — Intégration de `pv-module` (depuis Packagist, sans copier-coller)

| Commande | Objectif |
| --- | --- |
| `composer require salsabil-ennaiem/pv-module:^1.0` | Consommer le package tel quel ; verrouillé en `v1.0.2` (dist GitHub). |
| `php artisan vendor:publish --provider="SalsabilEnnaiem\PvModule\PvModuleServiceProvider"` | Publier `config/pv-module.php` et `lang/vendor/pv-module`. |
| `php artisan test --filter=PvModuleIntegrationTest` | Lancer le workflow d'intégration (création → envoi → signature → PDF FR/AR). |

---

## P4 — Types CDC + templates FR/AR + écran « Modèles de décision »

| Commande | Objectif |
| --- | --- |
| `php artisan make:seeder UmaDocumentTemplatesSeeder` | Générer le seeder des 7 types de documents CDC. |
| `php artisan db:seed --class=UmaDocumentTemplatesSeeder` | Insérer les templates par défaut (`pv`, `attestation`, `decision`, `arrete`, `invitation`, `diplome`, `fiche_acces`). |
| `php artisan make:filament-resource DecisionTemplate --generate` | Générer l'écran admin CRUD « Modèles de décision » (`/admin/decision-templates`). *(reconstitué)* |
| `php artisan test --filter=CdcDocumentTypesTest` | Valider types en config, template par type, création + PDF par type, RTL AR, écran admin (13 tests). |

---

## P5 — RBAC métier branchée sur les contrats du package

| Commande | Objectif |
| --- | --- |
| `php artisan make:policy PvPolicy` + policies métier (`Commission`, `Institution`, `DecisionTemplate`) | Créer les policies de délégation aux contrats du package. *(reconstitué)* |
| — | Implémentations manuelles dans `app/PvRules/` (`PvRules`, `ApprovalRules`, `ParticipantResolver`) et `app/PvSignatures/`. (pas de commande) |
| `php artisan test --filter=P5RbAcContratsTest` | Valider liens contrats, RBAC fail-closed, bascule driver `qualified`, seuils quorum/unanime, anti-IDOR (10 tests). |

---

## P6 — Module Réunions en Filament (SESSION ACTUELLE — commandes exactes)

### Génération CLI Filament (ressources + pages)

| Commande | Objectif |
| --- | --- |
| `php artisan make:filament-resource Reunion --generate` | Générer la ressource Réunions **depuis le modèle** (formulaire + table + pages CRUD). |
| `php artisan make:filament-resource Dossier --generate` | Idem `Dossier`. |
| `php artisan make:filament-resource Decision --generate` | Idem `Decision`. |
| `php artisan make:filament-page ManageReunionPresences --resource=Reunions/ReunionResource --type=Custom` | Page custom « enregistrement des présences » reliée au record (`/presences`). |
| `php artisan make:filament-page ManageReunionDecisions --resource=Reunions/ReunionResource --type=Custom` | Page custom « décisions de réunion » (`/decisions`). |
| `"no" \| php artisan make:filament-page ReunionCorbeille --resource=Reunions/ReunionResource --type=Custom` | Page corbeille (soft-deletes). Le `"no"` répond à la question interactive « relate to a record ? ». |

### Base de données / migrations

| Commande | Objectif |
| --- | --- |
| `php artisan migrate --force` | Appliquer les migrations (dont `2026_09_16_000001_create_reunions_module_tables.php`). |

### Tests

| Commande | Objectif |
| --- | --- |
| `php artisan test --filter=ReunionsModuleTest` | Suite P6 seule (9 tests) — débogage rapide. |
| `php artisan test` | Suite complète (47 tests / 239 assertions) — contrôle de régression. |

### Code style (Pint)

| Commande | Objectif |
| --- | --- |
| `vendor/bin/pint app tests database` | Normaliser le style sur tout le code + tests + data. |
| `vendor/bin/pint app/Console/Commands/DemoP6SliceVerticale.php app/Models/OdjTemplate.php app/Models/DecisionTemplate.php` | Pint ciblé sur les fichiers du moment. |
| `vendor/bin/pint app/Enums/DossierStatut.php app/Enums/InvitationStatut.php app/Enums/PresenceStatut.php app/Enums/ReunionStatut.php app/Enums/ReunionType.php` | Pint des 5 enums du module. |

### Démo / évidences (slice verticale)

| Commande | Objectif |
| --- | --- |
| `php artisan uma:demo-p6-slice` | Dérouler la slice verticale de bout en bout (dépôt → ODJ → présences → décisions → PV signé → attestation) et écrire `storage/app/private/evidence/p6-*.pdf`. Idempotente. |

### Évidences générées (P6)

| Fichier | Contenu |
| --- | --- |
| `storage/app/private/evidence/p6-pv-reunion-signe.pdf` | PV de réunion valide (45 Ko) — signature partie prenante. |
| `storage/app/private/evidence/p6-attestation-reussite.pdf` | Attestation de réussite AR (33 Ko). |

> **Note PDF** : les deux fichiers sont **non chiffrés** (pas de `/Encrypt`, `%%EOF` valide). Le rendu illisible dans Notepad vient de la compression FlateDecode (normale) — à ouvrir dans un lecteur PDF classique.

---

## P7 — Archivage & audit (mallette + traçabilité + rapports/états)

### Génération & base de données

| Commande | Objectif |
| --- | --- |
| `php artisan make:migration create_archivage_rapports_tables` | Migration unique : `documents`, `document_versions` (immuables), `rapport_etats`. *(reconstitué)* |
| `php artisan make:model Document` (+ `DocumentVersion`, `RapportEtat`, `AuditLog` adapté) | Modèles Eloquent P7. *(reconstitué)* |
| `php artisan make:policy DocumentPolicy` (+ `AuditLogPolicy`, `RapportEtatPolicy`) | Policies de lecture / abstraction RBAC. *(reconstitué)* |
| `php artisan migrate` | Appliquer `2026_09_20_000001_create_archivage_rapports_tables.php` — **exécutée avec succès**. |
| — | `config/archive.php`, `app/Services/{AuditLogger,ArchiveService,RapportService}.php` créés manuellement. (pas de commande) |

### Ressources Filament

| Commande | Objectif |
| --- | --- |
| `php artisan make:filament-resource Document --generate` | Écran « Mallette » (slug `mallette` pour éviter la collision avec le pv-module). *(reconstitué)* |
| `php artisan make:filament-resource RapportEtat --generate` | Écran « États / rapports » (`/admin/rapport-etats`). *(reconstitué)* |
| `php artisan make:filament-resource AuditLog --generate` | Écran « Journal d'audit » (lecture seule, Admin). *(reconstitué)* |

### Routes & vérifs

| Commande | Objectif |
| --- | --- |
| (édition `routes/web.php`) | Routes `admin/rapports-etats/{etat}/apercu` + `/{etat}/pdf` → `RapportController` avec `authorize('generer')`. |
| `php artisan route:list \| Select-String "rapport-etats"` | Vérifier routage Filament (`filament.admin.resources.rapport-etats.*`, tests) et web. |
| `php -l <fichier>` (en boucle) | Linter PHP après chaque correction de syntaxe (ternaire, braces des RelationManagers). |

### Tests

| Commande | Objectif |
| --- | --- |
| `php artisan test --filter=P7ArchivageAuditTest` | Suite P7 seule (15 tests / 88 assertions, puis 16/91 après le smoke des pages). |
| `php artisan test` | Suite complète (63 tests / 330 assertions) — contrôle de régression. |

### Code style (Pint)

| Commande | Objectif |
| --- | --- |
| `vendor/bin/pint app tests config database` | Normaliser tout le code + tests + config (32 fichiers réformattés ; tests toujours verts). |
| `vendor/bin/pint app/Filament/Resources/RapportEtats/Tables/RapportEtatsTable.php tests/Feature/P7ArchivageAuditTest.php` | Pint ciblé après le fix `IconColumn` + smoke test. |

## P8 — Moteur de workflow paramétrable en base (SESSION ACTUELLE — commandes exactes)

> ⚠️ Contrairement à P4/P6/P7, **aucun `make:filament-resource` n''a été utilisé en P8** : les modèles, le service moteur et le seeder ont été écrits à la main puis ajustés à la volée (les écrans Filament Workflow/Réclamation restent à finaliser).

### Base de données

| Commande | Objectif |
| --- | --- |
| `php artisan make:migration CreateWorkflowEngineTables` *(gabarit)* | Créer le fichier puis personnaliser : `workflow_definitions`, `workflow_transitions`, `workflow_guards`, `workflow_instances`, `workflow_audit_trails`, `reclamations`, `reclamation_discussions`, `reservations`. |
| `php artisan migrate` | Appliquer la migration (1ʳᵉ exécution → échec : index dupliqué `subject_type_subject_id` déjà créé par `morphs()` ; corrigé puis relancé). |
| `php -l <fichier>` (boucle) | Linter PHP après chaque correction (migration, services, seeder). |
| script temporaire `drop_partial_tables.php` | Purger les tables partiellement créées après l''échec (puis supprimé) — exécuté via `php drop_partial_tables.php`. |
| `php artisan db:seed --class=WorkflowSeeder` | Insérer les définitions A/B/C (21 transitions, 9 gardes). |

### Vérifications / smoke (scripts temporaires, supprimés ensuite)

| Commande | Objectif |
| --- | --- |
| `php smoke_seed.php` | Compter définitions/transitions/gardes + vérifier `config('workflow.*')`. |
| `php testsecrets` | — |

### Tests

| Commande | Objectif |
| --- | --- |
| `php artisan test --filter=P8WorkflowEngineTest` | Suite P8 seule (9 tests / 46 assertions). |
| `php artisan test` | Suite complète (72 tests / 376 assertions) — contrôle de régression global. |
| `./vendor/bin/pest tests/Feature/P7ArchivageAuditTest.php` | Baseline : 1 warning pré-existant (pas lié à P8). |

### Code style (Pint — à exécuter en fin de P8)

| Commande | Objectif |
| --- | --- |
| `vendor/bin/pint app tests database config docs` | Normaliser le style sur tout le code + tests + data (suggéré, non encore exécuté). |
