# DECISIONS_UMA.md — Registre des décisions (canonique)

> **Emplacement canonique** : ce fichier est la source de vérité des décisions du projet
> `uma`. `voyager/DECISIONS_UMA.md` est une copie **lecture seule** pointant ici (2026-09-16).
> Toute nouvelle décision est ajoutée ci-dessous, numérotée **ADR-xxxx**, format léger (une page).
> Le registre des hypothèses vit dans `ASSUMPTIONS.md` (règles R1/R2/R3).

## Pièces liées
- Questionnaire infrastructure CCK : `docs/mail_infrastructure_cck.md`
- Relance UMA + annexe matrice CDC (brouillon) : `docs/mail_relance_uma.md`
- Phase de transformation en modules interopérables : `docs/transformation-modules-interopérables.md`
- Démo P8.5a (scénario bout-en-bout) : `docs/demo_p8.5a.md`

---

## ADR-0001 — Tables & préfixe base de données

**Statut : ACCEPTÉ — puis AMENDÉ le 2026-09-16 par ADR-0003 (préfixe `uma_` appliqué dès
maintenant aux tables métier ; Plan B-V2 réservé à un éventuel préfixage framework/vendor).**
Décideur : éditeur · Date : 2026-09-16.

### Contexte
La plateforme sera déployée chez le CCK. Deux configurations possibles : un **schéma MySQL
dédié** (convention Laravel) ou la **coexistence** dans un schéma partagé avec d'autres
applications (une convention de préfixe serait alors nécessaire). La réponse CCK est attendue ;
l'application doit rester livrable sans être bloquée. Nom de base figé : **`uma_plateforme`**
(orthographe fixe, voir `AGENTS.md`).

### Décision
1. **Par défaut (Plan A)** — pas de préfixe ; base dédiée `uma_plateforme`, noms de tables nus
   (`users`, `dossiers`, `commissions`, …). Effort ≈ 0,5 j (gouvernance uniquement).
2. **Déclencheur (Plan B-V2)** — si le CCK confirme un schéma partagé ou impose une convention,
   activer le **préfixe global de connexion** (pas de renommage manuel) :

   ```php
   // config/database.php → connections.'mysql'
   'prefix'         => 'uma_',
   'prefix_indexes' => true,   // garde obligatoire dès le premier migrate:fresh
   ```

   Effort ≈ 1 j (voir Annexe A). Tout suit le préfixe via la grammaire Eloquent/Query Builder :
   modèles, migrations, pivots, `Rule::exists`, `DB::table`, seeders/factories, tests, et les
   tables framework (sessions, cache, jobs, notifications, password_reset_tokens, exports).
   Les tables du package `pv_module_*` deviennent `uma_pv_module_*` — c'est l'effet recherché
   en schéma partagé (anti-collision inter-apps) ; le package reprendra `pv_module_*` seul s'il
   tourne de manière autonome.
3. **Alternatives rejetées** :
   - renommage manuel `protected $table` + éditions de migration (~3-4 j, sur-ingénierie) ;
   - **deux connexions** pour un préfixage mixte (impossible sans modifier pv-module, interdit) ;
   - fork du package (interdit : une seule version, jamais de fork).
4. **Règle anti-SQL brut** (actée en `AGENTS.md`) : aucun nom de table en dur dans
   `whereRaw` / `DB::raw` / `DB::statement`. Grep actuel au 2026-09-16 : 0 occurrence.

### Risques résiduels (ordre de probabilité)
| # | Risque | Mitigation |
|---|--------|-----------|
| 1 | SQL littéral futur avec nom de table | Règle AGENTS + grep de contrôle dans la checklist |
| 2 | Noms d'index/FK si `prefix_indexes` changé après coup | Tester **avec** `prefix_indexes=true` dès le 1ᵉʳ `migrate:fresh` |
| 3 | SQL brut interne au package pv-module | Vérifier le grep du package avant activation |
| 4 | Scripts externes CCK (reporting, backups, restore) | Mention explicite dans la doc de livraison |
| 5 | `down()`/`dropForeign`/`dropIndex` sur base existante | Cycle `migrate:refresh` + `rollback --step=n` en validation |

### Fenêtre d'opportunité
Base vide + code non commité : la bascule Plan B reste bon marché (~1 j) jusqu'au déploiement.
Après imports massifs et pose CCK, ce serait une opération de production. La fenêtre bon marché
est gardée ouverte par défaut jusqu'à la réponse CCK.

---

## ADR-0002 — Phase 1 P8.5a : workflow de ré-inscription piloté par données + seeder recette

**Statut : ACCEPTÉ — non commité (validation utilisateur à venir).**
Décideur : éditeur · Date : 2026-09-16.

### Décision
1. **Moteur de workflow paramétré en base** (tables `workflow_*`, migration
   `2026_09_21_000001`) : définitions/states/transitions/guards/intances/audit-trails ;
   gardes de règle `document` et `data` évaluées par le moteur (`WorkflowEngine`).
   Workflow A2 « reinscription » seedé (`WorkflowSeeder`, idempotent `firstOrCreate`).
2. **UI Filament** : `EditDossier` expose dynamiquement « Démarrer le workflow », les
   transitions disponibles pour le rôle courant et « Historique du workflow » (blade de trace,
   payload/rôles protégés). Aucune colonne ajoutée hors fenêtre Phase 2 (payload JSON).
3. **Seeder comptes de recette** : `UsersSeeder` (7 comptes, un par `UserRole`, emails courts
   `admin@uma.tn`, `gestionnaire@uma.tn`, `agent@uma.tn`, `president@uma.tn`, `membre@uma.tn`,
   `directeur@uma.tn`, `doctorant@uma.tn` — domaine UMA Manouba, cf. tests existants), mot de
   passe commun `password`, idempotent `updateOrCreate` branché dans `DatabaseSeeder`.
   Passwords communs → **usage recette/dev uniquement**, jamais en prod (procédure auditée,
   cf. ASSUMPTIONS H08).
4. **Correctif CI (cause racine)** : `bootstrap/cache/config.php` figé depuis `.env`
   (`QUEUE_CONNECTION=database`, `DB_DATABASE=database.sqlite`) écrasait `phpunit.xml`
   (`sync`, `:memory:`) → mails/notifications *queued* jamais consommés en test + corruption
   de `database.sqlite` en runs parallèles. Fix : `php artisan config:clear` + garde
   `setUpBeforeClass()` **public** dans `tests/TestCase.php` (purge du fichier de cache).

### Bilan
- Suite complète : **92 tests / 92 passed / 473 assertions** (baseline P9).
- Nouveaux tests : `P8WorkflowReinscriptionTest` (5 tests / 44 assertions) : start,
  chemin nominal, garde document, branche dérogation, notification doctorant.

### Inventaire — fenêtre de schéma Phase 2 (à ouvrir, ~1 h)
Besoins identifiés en P8.5a qui impliqueront des colonnes/objets (aucun est créé aujourd'hui) :
| # | Besoin | Forme cible | P8 |
|---|--------|-------------|----|
| 1 | `niveau` d'inscription (actuellement payload) | colonne `dossiers.niveau` (int) suivie | 8.5a/b |
| 2 | Attestation générée (remise à l'étudiant) | lien `dossiers.attestation_id` → `documents` | 8.5a |
| 3 | Suivi paiement (réf. inscription.tn, mode, montant) | objet `paiements` (dossier → paiement) | 8.5a |
| 4 | Garde « document » formelle | `documents.type ∈ {rapport_avancement, pv}` vérifiée dès la branche passerelle (actuellement payload) | 8.5a |
| 5 | Réservation jury/soutenance (brique P8.5b) | liaison `reservations` ↔ dossier | 8.5b |
| 6 | Signature conformité R2 (A2) sur attestation | champs signature/trace dans `documents` | 8.5b |

### Notes
- `docs/structure-du-projet.md` était **déjà stagé** avant cette session (nuance : non commité,
  index pré-rempli) — pas de nouveau staging pour ce fichier.
- La décision de bascule **import natif Filament** (suppression des classes custom d'import) est
  **actée** et suivie en Phase 1b — voir `docs/transformation-modules-interopérables.md`.

---

## ADR-0003 — Préfixe `uma_` sur les tables métier (appliqué immédiatement)

**Statut : ACCEPTÉ ET IMPLÉMENTÉ (décision utilisateur).**
Décideur : utilisateur · Date : 2026-09-16.

### Décision
Le préfixe `uma_` est appliqué **dès maintenant** aux tables **métier** de l'application
(argument utilisateur : *il est plus facile de retirer un préfixe que de l'ajouter*). Périmètre
verrouillé via questionnaire :
- **Préfixées `uma_`** : toutes les tables métier — `uma_universites, uma_ecole_doctorales,
  uma_etablissements, uma_commissions, uma_decision_templates, uma_odj_templates,
  uma_reunions, uma_invitations, uma_presences, uma_dossiers, uma_decisions, uma_audit_logs,
  uma_documents, uma_document_versions, uma_rapport_etats, uma_workflow_definitions,
  uma_workflow_transitions, uma_workflow_guards, uma_workflow_instances,
  uma_workflow_audit_trails, uma_reclamations, uma_reclamation_discussions, uma_reservations`.
- **Exclues** : tables framework/vendor (`users`, `sessions`, `cache`, `cache_locks`, `jobs`,
  `job_batches`, `failed_jobs`, `notifications`, `exports`, `migrations`,
  `password_reset_tokens`), pivots `commission_user` et `reunion_dossier`, **package**
  `pv_module_*`.

### Mécanisme
Pas de préfixe de connexion global (il préfixerait aussi `pv_module_*` et les pivots) :
- renommage **per-table** dans nos migrations (`Schema::create`/FK/down) ;
- `protected $table = 'uma_x'` sur les **23 modèles métier** (`User` inchangé : table `users` ne
  porte pas le préfixe) ; 
- références brutes corrigées : `Rule::exists`/`lookupId` dans `TheseImport`
  (`uma_commissions`) et `EnseignantImport` (`uma_etablissements`), FK `users.etablissement_id`
  → `uma_etablissements`.

### Vérification
- `php artisan migrate:fresh --seed` ✅ (liste des tables conforme — cf. vérification shell).
- Suite complète : **92 tests / 92 passed / 473 assertions** ✅.
- Règle anti-SQL brut (`AGENTS.md`) de nouveau vérifiée : 0 nom de table en dur hors modèles.

### Conséquences
- **Plan B-V2 (ADR-0001)** : le préfixe global de connexion n'est **plus** le chemin pour les
  tables métier ; Annexe A est révisée en conséquence et ne restera pertinente qu'en cas de
  demande CCK de préfixage framework/vendor (sessions/cache/jobs…) — non appliqué aujourd'hui.
- **Snapshots livrables** : `livrables_cdc5/01_sources_application` et `02_sources_package`
  conservent l'ancien nommage (copies de livraison) — à **resynchroniser à la clôture**.
- La fenêtre « base vide » a été utilisée : la bascule a eu lieu avant déploiement et avant
  imports massifs, comme convenu.

---

## Annexe A — Checklist validation Plan B-V2 (~1 jour)

En cas d'activation du préfixe global :

1. `config/database.php` → `'prefix' => 'uma_'` + `'prefix_indexes' => true`
   (sur la connexion `mysql` ; SQLite/tests reçoivent le même comportement).
2. `php artisan optimize:clear` (purge `bootstrap/cache`, stockage `config:cache`).
3. Grep résiduel app + package + seeders :
   `whereRaw|DB::raw|DB::statement|selectRaw` → 0 nom de table en dur.
4. `php artisan migrate:fresh --seed` → vérifier `SHOW TABLES LIKE 'uma_%'` (liste complète,
   y compris `uma_migrations`, `uma_exports`, `uma_job_batches`, `uma_notifications`).
5. `php artisan migrate:refresh` (down + up) puis `php artisan migrate:rollback --step=3`
   → les `down()` et `dropForeign`/`dropIndex` suivent le préfixe.
6. `composer test` (92/92 attendus) — pipeline identique en SQLite mémoire.
7. Smoke manuel : export Filament (users) + import natif (users/dossiers) bout-en-bout.
8. Doc de livraison : `uma_migrations` remplace `migrations` ; signaler aux scripts CCK
   (reporting, backup/restore) les nouvelles références de tables.

---

## Annexe B — Matrice de conséquences SGBD (interne, décision FR/AR)

Décision d'indexation des libellés bilingues (JSON `{fr, ar, en}`) selon l'environnement réel.

| Environnement | JSON natif | Colonnes générées indexées | Conséquence lot FR/AR |
|---|---|---|---|
| MySQL ≥ 5.7 | Oui | Oui (avant 8.0 : sans support `utf8mb4` sur index JSON → préférer colonne virtuelle `VARCHAR`) | `json_extract` + index sur colonne générée |
| MySQL 8.x | Oui | Oui (complet) | **Chemin nominal** : colonnes générées + index `utf8mb4_0900_ai_ci` |
| MariaDB ≥ 10.2 | Stocké en `LONGTEXT` (simulation) | Colonnes **virtuelles** + index fonctionnel OK | Colonnes virtuelles `json_extract` index = substitution directe |
| MariaDB < 10.2 / MySQL < 5.7 | Non | Non | **Repli** : champs dédiés `nom_fr`/`nom_ar` (colonnes réelles) + adaptateur trait |

**Règle d'application** : la réponse CCK (question 2 du questionnaire infrastructure) branche la
décision. Le trait `HasTranslations` reste inchangé (abstrait du stockage) ; seul le mécanisme
d'index/accesseur varie.

---

## Registre consolidé (hérité de `voyager/DECISIONS_UMA.md`, maintenu ici depuis 2026-09-16)

> Toute mise à jour de statut A1-A5 / H01-H31 / ID-01→06 se fait **dans ce fichier**.

### A. Décisions à demander à l'UMA (30 min, ordre du dossier)

Légende statut : 🔴 = décision bloquante livraison · 🟠 = conforme par défaut, à confirmer ·
🟡 = validation d'alignement.

| # | Décision | Options (défaut sûr **en gras**) | Impact | Statut |
|---|---|---|---|---|
| **A1** | **R1 — PI** : nom / licence du package final, dépôt, cession (CDC §5) | **Conserver `SalsabilEnnaiem\PvModule` + cession par contrat** / renommage UMA | 1 commit Rector si option 2 ; identité des livrables CDC §5 | ✅ **Levé** (défaut sûr, 2026-09-15) |
| **A2** | **R2 — Signature** : niveau de conformité par document | **Signature simple + trace** / qualifiée eIDAS/PKI | Bascule `SIGNATURE_DRIVER=qualified` ; certificat PKCS#12 CCK | 🟠 |
| **A3** | **R3 — RTL** : modèles officiels d'attestations UMA | **Templates livrés (FR/AR) validés visuellement** / modèles définitifs UMA | Ajustement templates vues | 🟠 |
| **A4** | **Workflow A/B/C** : définitions, transitions, seuils/délais JORT | **Définitions paramétrées actuelles** / valeurs JORT officielles | Paramétrage en base uniquement ; re-seed | 🟡 |
| A5* | UAT commission pilote + import annuaire/inscription.tn | **Jeu réaliste fourni localement** / extraction réelle | Données de recette CSV/XLSX | 🟡 |

\* A5 secondaire : à traiter si le temps de réunion le permet (A1→A4 en priorité).

### B. Registre des hypothèses — app `uma` (H01→H31) + package (ID-01→06)

Statut : `V` = validée (défaut, décideur interne) · `P` = planifiée pour validation.

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date | Statut |
|----|-----------|----------------------|----------|------|--------|

> Le registre détaillé complet (H01→H31, ID-01→06, sections B.1→B.8) est conservé tel quel dans
> `voyager/DECISIONS_UMA.md` et référencé par la copie lecture seule ; les statuts seront
> déplacés ici lors de la prochaine passe de clôture (aucune divergence de décision à ce jour).

### C. Engagements de clôture (état au 2026-09-15)
- **A1** ✅ défaut sûr appliqué (`07_licence_identite/IDENTITE_DEPOT.md`) ; option renommage en réserve.
- **A2** 🟠 niveau signature à confirmer ; **A3** 🟠 fichiers graphiques PSD/AI à recevoir.
- **A4** 🟡 paramétrage workflow à réviser sur seuils JORT.
- **P9** ✅ clôturé (92/92 tests, 473 assertions au 2026-09-16, `composer audit` 0) ;
  dossier déploiement CCK livré dans `livrables_cdc5/06_installation_parametrage/`.

## ADR-0006 — Organigramme jsOrgChart + calendrier réunions (slots)

**Statut : PROPOSÉ (2026-09-21).** Détail : `docs/ADR-0006-organigramme-calendrier.md`.
Sidebar « Organigramme » hiérarchique (université → école → établissement →
commission → membre, rendu jsOrgChart local, détail + édition si policy
`update` existante (`InstitutionPolicy` / `CommissionPolicy` / `UserPolicy`,
sans doublon) ; `DateTimeSlotPicker` wooserv sur
`ReunionForm.date_debut` (date puis créneau, heures ouvrées, `date_fin`
auto +2h) ; page « Calendrier » (créer via bouton Filament, voir /
modifier / supprimer selon policies).