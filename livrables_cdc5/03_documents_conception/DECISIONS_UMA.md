# DECISIONS_UMA.md — Registre consolidé des décisions (P10 · Clôture)

> **État** : LIVRÉ (2026-09-15) — A1 appliqué **par défaut sûr** (UMA injoignable),
> P9 clôturé favorablement (rapport + dossier déploiement livrés).
> **Statut P10** : chaque ligne reçoit `validée` / `amendée` / `à corriger` + date + responsable.
> Les livrables physiques (`livrables_cdc5/`) sont prêts à l'assemblage après décision A1 finale.

Ce document consolide **`ASSUMPTIONS.md` (app `uma`) + `ASSUMPTIONS.md` (package `pv-module`)** —
format unique `ID · hypothèse · mécanisme de bascule · décideur · date · statut`.
Sources : `STRATEGIE_ROADMAP_PLATEFORME_UMA.md` §4/§8, `NOUVELLE_APP_GUIDE_INTEGRATION.md`
(décision 5 / risque 1), `Cdc.md` §5.
Dossier de réunion associé : `docs/DOSSIER_RE_SOLLICITATION_UMA.md`.

---

## A. Décisions à demander à l'UMA (30 min, ordre du dossier)

Légende statut : 🔴 = décision bloquante livraison avant pose de l'identité finale (R1) ·
🟠 = conforme par défaut, à confirmer · 🟡 = validation d'alignement.

| # | Décision | Options (défaut sûr **en gras**) | Impact | Statut |
|---|---|---|---|---|
| **A1** | **R1 — PI** : nom / licence du package final, dépôt de livraison, cession (CDC §5) | **Conserver `SalsabilEnnaiem\PvModule` + cession par contrat** / renommage UMA | 1 commit de renommage mécanique (Rector) si option 2 ; identité des livrables CDC §5 (dépôt, licence, auteur) | ✅ **Levé** (défaut sûr appliqué, 2026-09-15) |
| **A2** | **R2 — Signature** : niveau de conformité requis par document (PV commission / arrêtés / PV soutenance) | **Signature simple + trace (mécanisme + horodatage)** / qualifiée eIDAS/PKI | Bascule `SIGNATURE_DRIVER=qualified` ; pré-requis certificat PKCS#12 à provisionner (CCK) ; coût éventuel | 🟠 |
| **A3** | **R3 — RTL** : conformité des **modèles officiels d'attestations UMA** (en-têtes, logos, polices arabes) | **Templates livrés (FR/AR) validés visuellement** / modèles définitifs UMA à intégrer | Ajustement templates (confiné aux vues), nouveaux en-têtes/logos officiels | 🟠 |
| **A4** | **Workflow A/B/C** : validation des définitions, transitions et **seuils/délais JORT** (4ᵉ année 30 crédits + dérogation président ; 5ᵉ année arrêté rectorat ; quorum/unanimité réunions) | **Définitions paramétrées actuellement** / valeurs JORT officielles | Paramétrage en base uniquement (aucun code en dur) ; re-seed des définitions | 🟡 |
| A5* | UAT commission pilote + import annuaire/inscription.tn (rattaché P9) | **Jeu réaliste fourni localement** / extraction réelle thèses et enseignants | Données de recette, formats CSV/XLSX | 🟡 |

\* A5 est secondaire : à traiter seulement si le temps de réunion le permet (priorité A1→A4).

---

## B. Registre consolidé — app `uma` (H01→H31) + package `pv-module` (ID-01→ID-06)

Statut P10 : `V` = validée (par défaut, décideur interne) · `P` = planifiée pour validation
(UMA ou prochain lot) · `À corriger/Amendée` = à acter en clôture.

### B.1 Infrastructure / base de données (app)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H01 | SQLite en dev/tests suffisant | `DB_*` → MySQL + migrations déjà cross-SGBD | Éditeur / P9 | init | V |
| H02 | Déploiement CCK ; SQLite temporaire | MySQL/MariaDB 8+ (stack CCK) | P9 recette | P1 | V |
| H03 | Docs archivés sur disque `public` | Disque privé + policy téléchargement, ou S3/OBS | P9 / éditeur | P7 | V |
| H04 | Pas de queues en prod (synchrone) | Driver queue (database/Redis) + `ShouldQueue` prêts | P9 | P8 | V |

### B.2 Sécurité / authentification (app)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H05 | `Gate::before` super-admin (jamais compté dans la logique) | Retirer + policies fail-closed | P9 / admin | P5 | V |
| H06 | Signature qualifiée non branchée (driver `simple_image` en recette) | `SIGNATURE_DRIVER=qualified` + certificat PKCS#12 | Éditeur / CCK | P5 | P (≠ A2) |
| H07 | Auth Filament uniquement (pas de SSO/2FA) | Laravel Fortify (2FA) ou SSO CCK | P9 / admin | P1 | V |
| H08 | Admin par défaut `admin@uma.dz` jamais supprimé en dev | Audit prod : suppression + procédure auditée | P9 | P1 | V |

### B.3 Domaine métier (app)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H09 | Hiérarchie institutionnelle manuelle | Import CSV/XLSX (structures) | Éditeur / P9 | P3 | V |
| H10 | `annee_inscription` texte libre | Enum `NiveauInscription` + migration | Éditeur | P8 | V |
| H11 | Dossier → 1 doctorant (pas de co-tutelle) | Pivot `dossier_directeur` M2M | Éditeur / P9 | P8 | P |
| H12 | Commission → 1 discipline | `BelongsToMany` avec pivot | Éditeur | P1 | P |
| H13 | Réclamations texte seul | `documents()` polymorphique | Éditeur | P8 | V |
| H14 | Ordre transitions par `sort`, pas d'acyclicité | Trigger/seeder guard anti-cycles | P8 / éditeur | P8 | V |

### B.4 Workflow (app, rattaché A4)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H15 | Gardes `contract` résolues par container | Hooker singleton dans `AppServiceProvider` | P8 | P8 | V |
| H16 | Notifications de transition role-based | `notifications` → `user_id` + adapt `resolveRecipients` | Éditeur | P8 | P |
| H17 | Action bord `reclamation.actualiser` = contrat implicite | Contrat `HasWorkflowStatus::syncFromInstance` | P9 | P8 | V |

### B.5 Module pv-module (package, rattaché A1/A2/A3)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H18 | Package consommé tel quel (jamais modifié) | Fork possible via VCS repo | Éditeur | P3 | V (pas de fork) |
| H19 | Package contrôle ses routes (`admin/documents`) | `routes.name_prefix` config | Éditeur | P3 | V |
| H20 | Types CDC fixés dans `config/archive.php` | Ajouter type + migrer `rapport_etats.pv_type` | Éditeur | P4 | V |
| **ID-01** | **PI / namespace `SalsabilEnnaiem` (R1)** | Renommage mécanique (Rector) + diff + tests verts ; tag de livraison final posé en P10 | Éditeur + **UMA** | P2 | **P (A1)** |
| **ID-02** | **Signature simple `simple_image` (R2)** | `SignatureStrategy` simple/qualifiée ; colonnes preuve non destructives | Éditeur + **UMA** | P2 | **P (A2)** |
| **ID-03** | **Rendu RTL piloté par locale (R3)** | Locale par doc dans `PdfService` ou `direction` par section | Éditeur + **UMA** | P2 | **P (A3)** |
| ID-04 | App hôte toujours authentifiée | Middleware configurable | Éditeur | P2 | V |
| ID-05 | Signatures `storage_disk = local` | `public`/S3 via config | Éditeur | P2 | V |
| ID-06 | Seul `pv` livré par défaut | Déclarer types dans `config('pv-module.types')` | Éditeur | P2 | V |

### B.6 Architecture / monolithe (app)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H21 | Monolithe (modules = dossiers, pas de packages) | Extraire chaque module en package Composer interne | Éditeur / P10 | P1 | V |
| H22 | Migration « modules interopérables » différée après recette | Projet P10/P11 post-déploiement | Éditeur | P1 | V |
| H23 | Filament = seule interface admin (pas d'API) | `routes/api.php` + transformers | Éditeur | P1 | P |
| H24 | Config versionnée + surchargée `.env` | Documenter variables critiques dans `.env.example` | P9 | P1 | V |

### B.7 Déploiement / recette (app, rattaché A5)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H25 | Déploiement CCK / normes RNU | Config mail/queue/cache/filesystems si hébergeur externe | P9 | P1 | V |
| H26 | Tests SQLite en CI ; MySQL en recette | Service MySQL en CI (Docker) | P9 | P7 | V |
| H27 | Aucun IDOR détecté (P7/P8) | Audit IDOR complet en P9 | P9 | P7 | V |
| H28 | `.env` jamais versionné | Secrets CI/CD | P9 / déploy | P1 | V |

### B.8 UI / UX (app)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|
| H29 | Interface exclusivement en français | `lang/` + `app.locale`/`fallback_locale` + vues `__()` | Éditeur | P1 | P |
| H30 | PDF portrait A4 par défaut | Formats A3/Letter dans `format_papier` du `RapportEtat` | Éditeur | P7 | V |
| H31 | Emails Markdown | Templates Blade HTML + `$html` dans Content | Éditeur | P6 | V |

---

## C. Engagements de clôture - etat execute le 2026-09-15

- **A1** ✅ **Levé par défaut sûr** : identité de livraison consignée dans `uma/livrables_cdc5/07_licence_identite/IDENTITE_DEPOT.md` (namespace `SalsabilEnnaiem\PvModule` conservé, licence MIT, cession par contrat). Option renommage en réserve (1 commit Rector) - a acter si l'UMA l'exige.
- **A2** 🟠 : niveau signature restant à confirmer (defaut = simple, bascule `SIGNATURE_DRIVER=qualified` possible).
- **A3** 🟠 : fichiers graphiques PSD/AI a recevoir de l'UMA.
- **A4** 🟡 : paramétrage workflow par défaut conservé, a réviser sur les seuils JORT avec l'UMA.
- **P9** ✅ **Clôturé** : rapport de recette + dossier de déploiement CCK/RNU livrés dans `uma/livrables_cdc5/06_installation_parametrage/` (87/87 tests verts, 429 assertions, `composer audit` 0).
- **Post-clôture** : assembler physiquement `uma/livrables_cdc5/` (archive ZIP), commit monolithe + package, push.
