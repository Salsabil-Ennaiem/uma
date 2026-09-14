# Commandes utilisées — Plateforme UMA (par étape : P1, P3, P4, P5, P6)

> La CLI Filament est **la source des ressources** (`make:filament-resource --generate`, `make:filament-page`) : on génère, puis on personnalise les fichiers produits.
> **⚠️ Provenance** : les commandes P1/P3/P4/P5 proviennent de **sessions antérieures** — listées ici **reconstituées d'après le README et les évidences** (pas une trace verbatim). Les commandes **P6** sont celles de la **session actuelle** (exactes).

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