# Guide d'intégration — Nouvelle application « Gestion Documentaire + Workflow + Réunions »

> **Objectif** : bâtir une nouvelle application centrée sur la **gestion documentaire** (workflow, signature, types de documents, archivage, templates) et les **réunions**, en **réutilisant au maximum le projet actuel** (`voyager`).
>
> **Stack cible** : Laravel 13 · Filament 5 · PHP 8.2+ · **zéro dépendance payante / minimale**.
>
> **Principe** : tout ce qui est déjà *générique* et déjà écrit → on le réutilise (package `salsabil-ennaiem/pv-module` + code « meetings » extrait comme référence). Tout ce qui est *spécifique au nouveau métier* → on l'écrit dans la nouvelle app (Filament + code maison).

---

## 1. Inventaire : ce que le projet actuel nous donne déjà

### 1.1 Le joyau réutilisable : `packages/salsabil-ennaiem/pv-module`

C'est un **moteur documentaire autonome**, déjà extrait hors de Voyager, compatible Laravel 10→13 :

| Capacité | Détail |
|---|---|
| **Workflow** | `brouillon → en_attente → valide` (+ rejet → retour brouillon), envoi aux participants, délais |
| **Versions** | chaque modification enregistre une version consultable + statut |
| **Signatures** | dessin (canvas) OU upload d'image, tailles/MIME configurables |
| **Notifications** | canal `database` (cloche) + e-mail |
| **PDF** | mPDF, template Blade personnalisable, sections (header/middle/signature) |
| **Templates** | modèles de documents réutilisables (`PvTemplate`), seeder par défaut, éditeur de rubriques (drag & drop, alignement) |
| **i18n** | fr / ar / en (JSON) — indispensable pour les attestations AR |
| **Multi-types** | `config('pv-module.types')` : `pv`, `attestation`, `arrete`, `decision`, `invitation`, `diplome`… |
| **Extensibilité** | 3 contrats remplaçables sans toucher au code : `CanManagePv` (RBAC), `ApprovalRules` (seuils), `ParticipantResolver` (qui signe) |

> **Il couvre déjà ~40 % du périmètre cible** (documents + signature + templates + versions + notifications + PDF FR/AR).

### 1.2 Le code « meetings » présent dans Voyager (à réutiliser comme **référence**)

Non extrait en package (domaine trop spécifique), mais **modèle parfait pour réécrire le module réunions en Filament** :

| Briques Voyager | Fichier | Ce qu'on en tire pour la nouvelle app |
|---|---|---|
| Modèle Réunion | `app/Models/Reunion.php` | statuts (`brouillon/planifiee/en_cours/terminee/annulee`), types (présentiel/visio/hybride), ODJ, `presedent_Reunion`, `can_generate_pv` |
| Modèle Invitation | `app/Models/Invitation.php` | réponses accepter/refuser/excuser + justificatif, présence présent/absent/excusé |
| Modèle PV | `app/Models/PV.php` + `PVValidation.php` | workflow de validation, signatures par section |
| Templates PDF | `app/Models/PdfTemplate.php` | templates par utilisateur + template global + reset |
| Services | `app/Services/PdfService.php`, `SignatureService.php`, `NotificationService.php`, `InvitationService.php` | logique de génération PDF, signature, notifications, présence |
| Contrôleurs | `PVController`, `ReunionController`, `SignatureController`, `InvitationEtPresenceController` | contraintes métier (date_fin passé pour PV, deadline signature, etc.) |
| Policies | `ReunionPolicy`, `PVPolicy`… | matrice de rôles admin / chef / membre |
| Docs | `docs/PV_MODULE_INTEGRATION_GUIDE.md`, `LOT2_FILAMENT_PLAN.md`, `WORKFLOW_ET_COMPTES.md` | procédure d'intégration + plan Filament déjà rédigés |

### 1.3 Ce qui est DÉJÀ fait (artefacts de conception, gains de temps importants)

- ✅ Le **moteur de workflow documentaire** (signature + versions + templates) → package
- ✅ Les **3 diagrammes d'états** (réunion/PV dans `WORKFLOW_ET_COMPTES.md`, workflows CDC dans `Cdc.md`)
- ✅ La **matrice acteur × action** (tableaux du `WORKFLOW_ET_COMPTES.md` + §5 du résumé CDC)
- ✅ La **procédure d'intégration package** dans une app neuve (`docs/PV_MODULE_INTEGRATION_GUIDE.md` §2)
- ✅ Le **plan Filament complet** du back-office (`LOT2_FILAMENT_PLAN.md`)

---

## 2. Les 6 décisions stratégiques AVANT de créer la nouvelle app

1. **Le package est la colonne vertébrale** → on le **public** une fois (Packagist ou Git) et on le consomme par Composer dans la nouvelle app. Jamais de copier-coller du code package dans la nouvelle app.
2. **Module réunions = code app maison** (Filament), inspiré du code Voyager, **pas un second package** — domaine trop spécifique pour être partagé.
3. **Capacités PDF étendues de Voyager** (batch d'impression, en-têtes/logos par structure, formats variés) → **absorbées dans `pv-module` (v1.1)**, pas dans un package séparé.
4. **UNE seule console back-office** (CDC §5 : « un seul espace back-office pour le CMS et la plateforme ») → **1 panneau Filament** avec groupes de navigation par rôle, **pas 2 panneaux séparés**. L'isolation se fait par `canAccessPanel()` + `->visible()`/Policies, pas par un changement d'URL.
5. **Propriété intellectuelle (CDC §5)** : tous les codes/documents sont **cédés à l'UMA** → le package doit être livré sous une identité/trim d'auteur cohérente avec la prestation (le repo actuel est `salsabil-ennaiem/pv-module` sous licence MIT). Trancher **avant livraison** : attribution finale, licence, dépôt du code source + docs de conception (un livrable CDC).
6. **Règles métier en base, jamais en dur** : le CDC répète « la spécification détaillée du workflow sera faite durant la phase d'analyse » et « le workflow doit s'aligner avec les textes de lois » → moteur de workflow + règles (seuils, délais, conditions JORT) **paramétrables** dès le départ.

---

## 3. Étapes d'intégration dans la nouvelle app

### Étape 0 — Créer le socle (Laravel 13 + Filament 5) — ½ journée

```bash
laravel new gestion-docs-reunions
cd gestion-docs-reunions
composer require filament/filament:"^5.0" -W
php artisan filament:install --panels
php artisan migrate
```

- **1 seul panneau** (conformité CDC §5 « même console d'administration ») : `filament:install --panels` puis n'en garder qu'un (ex. `/admin`), avec **groupes de navigation** par rôle (Gestion, Réunions, Documents, Archives, Paramétrage). L'isolation école/commission = `canAccessPanel()` + `->visible()`.
- Activer l'auth Filament (login/reset/profil) — **pas de Breeze** (règle d'économie).
- RBAC **natif** : enum `UserRole` + colonne `users.role` + `canAccessPanel()` fail-closed (rôle absent → 403) + Gates/Policies. **Pas de spatie/laravel-permission.**

**Sortie** : app qui tourne, 1 back-office unique, auth, isolation par rôle.

### Étape 1 — Intégrer `salsabil-ennaiem/pv-module` (le gros du gain)

```jsonc
// composer.json — consommer le package
{
  "repositories": [
    { "type": "path", "url": "../voyager/packages/salsabil-ennaiem/pv-module", "options": { "symlink": true } }
    // OU { "type": "vcs", "url": "https://github.com/tonorg/pv-module.git" }
  ],
  "require": { "salsabil-ennaiem/pv-module": "@dev" }
}
```

```bash
composer update salsabil-ennaiem/pv-module
php artisan vendor:publish --tag=pv-module-config --force
php artisan vendor:publish --tag=pv-module-lang --force   # fr/ar/en
php artisan migrate --force
php artisan db:seed --class="SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder"
php artisan storage:link
```

Puis dans `config/pv-module.php` :
- `'user_model' => \App\Models\User::class`
- `'routes.prefix' => 'admin/documents'` (intégré aux panneaux Filament)
- `'types' => ['pv', 'attestation', 'arrete', 'decision', 'invitation', 'diplome']`

Vérifier : `php artisan test` du package + test manuel d'un PV signé → PDF FR/AR.

**Sortie** : le moteur documentaire fonctionne dans la nouvelle app.

### Étape 2 — Déclarer les types de documents du CDC (via config + templates)

Réutiliser le catalogue documentaire du résumé CDC (§4) comme **seed initial de templates** :

| Type `config('pv-module.types')` | Documents couverts |
|---|---|
| `pv` | PV de réunion, PV de soutenance |
| `attestation` | inscription (par niveau), présence, paiement étrangers, شهادة حضور/ترسيم/مغادرة (AR RTL) |
| `decision` | décisions de dérogation, décisions de commission (modèles paramétrables + mail associé) |
| `arrete` | arrêté de composition de jury, arrêtés d'inscription |
| `invitation` | invitations officielles jury + convocations |
| `diplome` | diplôme (modèle MESRS) |
| `fiche_acces` | fiche des paramètres d'accès (imprimable + mail) |

Pour chaque type : template via `PvTemplate` (sections, marges, orientation, logo, FR/AR).

**Le CDC exige en plus (1.13) que chaque état soit :**
- éditable depuis un module « rapports et états » unique ;
- imprimable **en batch** (masses) ;
- rendu **HTML ou PDF au choix** par état ;
- avec en-tête/pied de page, **marges, orientation et format papier** réglables par état.

→ C'est la capacité à **absorber dans `pv-module` v1.1** (moteur PDF étendu), pas à réécrire par type.

---

### Étape 3 — Brancher la RBAC métier sur les contrats du package (sans toucher au package)

Créer 3 classes dans la nouvelle app qui implémentent les contrats :

| Contrat | Implémentation app (`config/pv-module.php`) | Rôles CDC couverts |
|---|---|---|
| `CanManagePv` → `App\PvRules\PvRules` | `canCreate/canUpdate/canSend/canValidate/canSign/canDelete/canDownload` | président commission, agent, admin école doctorale, rapporteur… |
| `ApprovalRules` → `App\PvRules\ApprovalRules` | seuils de validation (quorum, unanimité, délais réglementaires) | PV de commission |
| `ParticipantResolver` → `App\PvRules\ParticipantResolver` | résolution des signataires depuis entités métier (membres commission, jury) | membres de jury, commissions |

**Correspondance CDC §2 « espaces numériques » → ressources Filament** (à coder en Policies, pas en panneaux séparés) :

| Espace CDC | Groupe Filament | Règles de visibilité clés |
|---|---|---|
| Administrateurs | Système | tout CRUD, paramétrage global, archives |
| Établissement / Université | Institution | lecture école/établissement, validation hiérarchique |
| Cadres administratifs | Guichet | vérifier pièces, valider reçus, arrêtés, générer attestations |
| Président de commission | Commissions | CRUD réunions, décisions, PV, désignation jury |
| Membre de commission | Commissions | ODJ + dossiers **de sa commission uniquement**, voter, valider PV (les présents) |
| Rapporteur | Jury | désignations, dépôt rapport/avis, relances |
| Membre de jury | Jury | invitations, PV de soutenance |
| Directeur de thèse | Encadrement | ses doctorants, avis, autorisations, rapports |
| Doctorant / Candidat | Espace usager | mallette numérique, demandes, attestations, réclamations |

> Règle d'or : **toutes les actions Filament passent par les gardes du package** — jamais de logique dupliquée dans le panneau.

### Étape 4 — Module Réunions (le plus gros travail à écrire, modèle = Voyager)

Réécrire en Filament, en s'inspirant des models/controllers/policies de Voyager :

- [ ] `Reunion` : entité + statuts (brouillon→planifiée→en_cours→terminée/annulée), dates, lieu, type (présentiel/visio/hybride), ODJ
- [ ] **Choix de la discipline → membres de commission ajoutés automatiquement** (CDC : chaque commission gère ses propres réunions)
- [ ] **ODJ paramétrables** (modèles d'ordre du jour éditables) — comme les templates de décision
- [ ] **Dossiers à l'ODJ** : sélection « facile et intuitive » des demandes en attente (auto-alimenté par les demandes validées administrativement, cf. CDC)
- [ ] **Invitations** : convocation auto par mail + **lettres d'invitation officielles imprimables** (PDF via `pv-module`)
- [ ] **Liste de présence** post-réunion (présent/absent/excusé) — réunions en présentiel
- [ ] **Décisions par dossier** : saisie par **l'administrateur OU le président de la commission** (double acteur CDC §réunions) ; modèles de décision paramétrables `label + contenu d'email prédéfini` ; décisions visibles dans la **mallette par année d'inscription**
- [ ] **Export décisions → partie d'un projet de PV** + opération d'intégration **accessible au président pour validation**
- [ ] **PV de réunion** : reprise du workflow Voyager (`date_fin` passée → générer le PV) branché sur `pv-module`
- [ ] **Validation du PV par les personnes ayant assisté à la réunion** + notification des parties prenantes + CC école doctorale (`ApprovalRules` adaptées)
- [ ] **Notifications configurables par acteur** (doctorant, directeur de thèse, membre…) — système de notification des réunions paramétrable
- [ ] **Corbeille des réunions** supprimées + relance de nouvelle réunion dans les délais réglementaires
- [ ] **Journal de logs** de toutes les actions sur la réunion (exigence explicite CDC → table `audit_logs`)

### Étape 5 — Archivage + audit + rapports (1.13 / 1.14)

- [ ] `Document` / `DocumentVersion` / `DocumentType` polymorphiques (rattaché au doctorant/réunion/décision) — modèle inspiré du PV package
- [ ] **Dossier numérique / mallette** par entité : arborescence, versionnage, rétention ; décisions « visibles par année d'inscription » ; archivage dès la création
- [ ] **Journal d'audit append-only** : `audit_logs` (qui, quand, quoi, avant/après, IP)
- [ ] **Module « rapports et états »** (absorbe PDF étendu de Voyager dans `pv-module`) : batch, HTML **ou** PDF par état, en-têtes/pieds, marges, orientation, format papier

### Étape 6 — Moteur de workflow paramétrable en base + communications (1.12)

- [ ] `WorkflowDefinition` / `WorkflowInstance` / `Transition` en base — le CDC exige un workflow **alignable sur les textes de loi** → **paramétrable**, pas codé en dur
- [ ] Déclencher les transitions depuis les actions Filament
- [ ] **Mails dynamiques (1.12)** : modèles de mails à **variables/tags**, prévisualisation avant envoi, en-têtes/pieds, **envoi à une liste filtrée** depuis n'importe quel tableau (éligible dès qu'un écran liste doctorants/directeurs/responsables)
- [ ] Notifications par transition (déclenchées par les changements d'état) — ex. « attestation prête dans 72 h »
- [ ] Relances automatiques (rapporteurs en retard), contrôle chevauchement jury/salles

### Étape 7 — Recouvrement des 3 workflows métier du résumé

En branchant les documents (`pv-module`) + réunions + workflow en base :

- [ ] **Workflow A — Inscription 1ᵉʳ→5ᵉ année** : dépôt → directeur → admin → ODJ commission → décision → PV → paiement inscription.tn + upload reçu → validation → attestation FR/AR → archivage ; variations par niveau (4ᵉ : validation 30 crédits + dérogation → président université ; 5ᵉ : + arrêté d'inscription rectorat)
- [ ] **Workflow B — Soutenance** : conditions d'éligibilité (≥ 3 inscriptions, crédits validés, rapport d'approbation de l'encadreur, règles JORT) → **2 dépôts initiaux + copie finale** + antiplagiat (hook/statut) → rapporteurs (2 avis favorables requis) → **dossier rectorat** (2 rapports, rapport encadrant, PV commission, fiche thèse.tn, plagiat, inscriptions, crédits) → arrêté jury validé président université → planification (contrôle chevauchement jury/salles) → convocations → PV jury → diplôme MESRS → notification « diplôme disponible »
- [ ] **Workflow C — Demandes diverses** (titre, langue, directeur, discipline, abandon, cotutelle) + **réclamations/ticketing** : dépôt, états et priorités paramétrables (très urgente / prioritaire / moyennement urgente), **discussions autour d'une même réclamation**, clôture

### Étape 8 — Recette

- [ ] Tests UAT par commission pilote
- [ ] Imports CSV (enseignants) + **moulinet d'import des thèses en cours** (CSV/XLSX/XML/JSON, mappage + validation avant intégration) — déviation `.xlsx` si nécessaire
- [ ] Exigences §6 vérifiées : recherche multi-colonnes, filtres combinés ET/OU, colonnes personnalisables, exports — **couvertes nativement par Filament** (Table/global search/bulk), aucun code à écrire
- [ ] `composer audit` 0 critique, revue des Policies (aucun IDOR), `route:list` audité (aucune route publique involontaire)

---

## 4. Règles d'économie (zéro dépense additionnelle)

| Besoin | Solution | Interdit |
|---|---|---|
| Gestion documents | `pv-module` (déjà écrit) | racheter un DMS |
| Signatures | signature électronique simple du package (OTP/hash + horodatage) — **faire trancher la conformité par l'UMA** | service de signature qualifiée payant (tant que non exigé) |
| PDF | mPDF (déjà dans le package) | DomPDF | pdf-lib… |
| Administration | Filament 5 (LTS, gratuit) | autre panneau d'admin |
| RBAC | natif (enum + Gates + Policies) | spatie/laravel-permission |
| Export livrables | CSV maison | maatwebsite/excel (sauf exigence `.xlsx` explicite) |
| Auth | Filament | Breeze/Jetstream |
| Archives | stockage interne (SFTP/S3 du CCK) | DMS SaaS |

---

## 5. Tableau récapitulatif : ce qui est RÉUTILISÉ vs RÉÉCRIT

| Fonctionnalité (périmètre restreint) | Source | Effort |
|---|---|---|
| Workflow documentaire (brouillon→validé) | `pv-module` | 0 (intégration) |
| Signature (dessin/upload) | `pv-module` | 0 |
| Versions / historique documents | `pv-module` | 0 |
| Templates + éditeur de rubriques | `pv-module` | 0 |
| PDF FR/AR + mPDF | `pv-module` | 0 |
| Notifications (cloche + mail) | `pv-module` | 0 |
| **Types de documents** (attestations, arrêtés, décisions, invitations, diplôme) | `pv-module` types + templates | Moyen (seed de templates) |
| **Réunions / invitations / présence / corbeille** | Code Voyager → **réécrit en Filament** | **Élevé (le plus gros)** |
| **ODJ paramétrables** | à écrire (maison) | Moyen |
| **Export décisions → PV (Excel)** | CSV maison + `pv-module` | Faible |
| **Workflow en base** (A/B/C paramétrable) | à écrire (maison) | Élevé |
| **Archivage + dossier numérique** | à écrire (maison, inspiré package) | Moyen |
| **Journal d'audit** | à écrire (table `audit_logs`) | Faible |

---

## 6. Ordre de livraison recommandé (8 étapes)

| # | Étape | % cumulé | Sortie |
|---|---|---|---|
| 0 | Socle Laravel 13 + Filament 5 + RBAC natif | 5 % | app + 1 back-office + auth |
| 1 | Intégration `pv-module` | 25 % | moteur documentaire vivant |
| 2 | Types de documents + templates FR/AR | 35 % | attestation + invitation générées |
| 3 | RBAC métier branchée sur les contrats | 45 % | aucune action sans garde package |
| 4 | Module Réunions (Filament) | 70 % | réunion → ODJ → présence → décisions → PV |
| 5 | Archivage + audit | 80 % | dossier numérique + traçabilité |
| 6 | Workflow en base (A/B/C) | 92 % | parcours inscription + soutenance |
| 7 | Recette, imports, durcissement | 100 % | validation commission pilote |

---

## 7. Points d'attention / risques (à lever tôt)

1. **Signature électronique** : le CDC parle de « validation » mais pas de signature qualifiée/chiffrée → **trancher avec l'UMA** (risque de non-conformité juridique des PV/arrêtés). Le package fait de la signature simple (hash + horodatage) — documenter la limite.
2. **Bilinguisme AR/FR** : attestations arabes = pas une simple traduction (RTL, polices, en-têtes officiels). Le package gère déjà fr/ar/en en JSON, mais **valider la mise en page RTL dès le seed de templates**, pas en rattrapage.
3. **Workflow codé vs. paramétrable** : ne pas coder les transitions en dur — le CDC exige l'alignement sur les textes de loi → moteur en base (Étape 6) avant de recouvrir les workflows A/B/C.
4. **Le package doit être versionné et publié** (tag `v1.0.0` → `v1.1.0` pour le PDF étendu) : toute évolution se fait **dans le package**, jamais en copie locale.
5. **Cession de propriété à l'UMA (CDC §5) vs. package personnel `salsabil-ennaiem/pv-module`** : anticiper — nom d'auteur final, licence, dépôt du code source + artefacts de conception comme **livrables** ; ne pas découvrir cette question à la recette.
6. **Une seule console back-office (CDC §5)** : ne pas multiplier les panneaux Filament ; grouper par rôles au sein d'un panneau unique, avec gating fail-closed.
7. **Filament couvre déjà les exigences §6** (filtres ET/OU, colonnes personnalisables, recherche globale, exports) → ne pas réécrire de tableaux ; temps gagné à réinvestir sur les workflows métier.

---

*Sources dans le projet actuel* : `packages/salsabil-ennaiem/pv-module/README.md` · `docs/PV_MODULE_INTEGRATION_GUIDE.md` · `docs/PROJECT_STRUCTURE.md` · `WORKFLOW_ET_COMPTES.md` · `LOT2_FILAMENT_PLAN.md` · `Cdc.md`.