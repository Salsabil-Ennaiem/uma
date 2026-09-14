# Bugs & problèmes rencontrés — Plateforme UMA (par étape : P1, P3, P4, P5, P6)

> Seuls les **vrais bugs / obstacles** (bloquants ou subtils) sont listés, avec cause racine et solution.
> P1/P3/P4/P5 : reconstitués d'après le README (sessions antérieures). P6 : session actuelle (exacts).
> Mise à jour au fil de l'eau.

---

## P3 — Intégration de `pv-module`

1. **Tags Git obsolètes / mal nommés (bug package, sans correction locale)**
   - **Symptôme** : après `composer require salsabil-ennaiem/pv-module`, le `v1` et `v1.0.0` pointent sur un **ancien commit** (avant R2/R3). Les tags `pv-module-config` / `pv-module-lang` du prompt ne correspondent pas aux tags réels publiés (`pv-config` / `pv-lang`).
   - **Résolution (éditeur)** : tags `v1.0.1` (R2+R3) et `v1.0.2` (fix routage) publiés et poussés ; dépendance locale remplacée par Packagist.
   - **Leçon** : vérifier le commit réel d'un tag avant de dépendre d'une version.

2. **Noms de routes codés en dur dans le package**
   - **Symptôme** : les notifications du package appelaient `route('pv-module.show')` — imbréable si l'app liste ses routes autrement.
   - **Résolution (éditeur, v1.0.2)** : le package lit `config('pv-module.routes.name_prefix', ...)`. Préfixe d'URL `admin/documents`, préfixe de noms `pv-module.`.

---

## P4 — Types CDC + templates FR/AR + écran « Modèles de décision »

3. **Adaptations Filament 5 (cassé sous Filament 3/4)**
   - **Symptôme** : les imports « v3/v4 » plantent sous Filament 5.8.
   - **Adaptations documentées** :
     - `form(Schema $schema)` dans `Filament\Schemas\Schema` (plus `Filament\Forms\Form`) ;
     - actions de table → `Filament\Actions\EditAction|DeleteAction|BulkActionGroup|DeleteBulkAction` (le package `filament/tables` ne les définit plus) ;
     - `$navigationIcon` et `$navigationGroup` typés `BackedEnum|null` / `UnitEnum|null`.
   - **Leçon** : ces 3 points sont récurrents (retrouvés en P6 au point 5) — à intégrer d'office dans toute ressource Filament 5.

4. **Capacité manquante détectée (différé, non corrigé localement)**
   - **Symptôme** : pas d'impression **batch** ni de logos/en-têtes paramétrables par structure.
   - **Résolution** : sous-prompt package rédigé (`Sous-prompt P4 — PdfService batch & logos`, tag `v1.1.x`) — à exécuter après validation de P4. **Aucune correction locale** (règle « le package n'est pas modifié »).

---

## P5 — RBAC contrats

5. **Aucun bug bloquant documenté.**
   - Architectures validées par les tests P5 : liens contrats (`CanManagePv`, `ApprovalRules`, `ParticipantResolver`), RBAC fail-closed par rôle, gate package obligatoire (403), bascule `SIGNATURE_DRIVER=qualified`, seuils `unanimous`/`quorum`, anti-IDOR commission/décision/PV.
   - **Point de vigilance acté** : `Gate::before` accorde tout à l'Admin — il ne faut **jamais** compter dessus pour la logique métier ; toujours appeler les contrats du package directement (cf. P6 point 8).

---

## P6 — Module Réunions (SESSION ACTUELLE — bugs réels)

6. **`Call to undefined cast [App\Enums\DossierStatut]` — 9/9 tests en échec (bloquant)**
   - **Cause** : l'enum `DossierStatut` était déclaré dans `app/Enums/PresenceStatut.php` (violation **PSR-4**) ; à l'autoload frais des tests, Composer ne trouve pas `DossierStatut.php`.
   - **Solution** : créer `app/Enums/DossierStatut.php`, retirer le bloc dupliqué de `PresenceStatut.php`.
   - **Leçon** : « marche en dev » ≠ fiable — une classe hors de son fichier PSR-4 explose dès que l'autoloader est reconstruit.

7. **Erreur 500 : `form()` `must be of type Filament\Forms\Form, Filament\Schemas\Schema given` (bloquant)**
   - **Cause** : signature Filament v3/v4 sur un projet **Filament 5.8** (`form(Schema $schema): Schema` attendu).
   - **Solution** : `form(Schema $schema): Schema` + `use Filament\Schemas\Schema;` sur `ManageReunionPresences` **et** `ManageReunionDecisions`.
   - **Corollaire de recherche** : le helper global `table()` n'existe pas en Filament 5 ; la table d'une page se construit via `Table::make()` + méthode `table(Table $table)`.

8. **Pages custom accessibles sans habilitation — trou dans la RBAC (sécurité, bloquant)**
   - **Symptôme** : un membre non habilité recevait **200** sur `/presences` et `/decisions`, alors que `Gate::allows('update', ...)` renvoie `false`.
   - **Cause** : `make:filament-page --type=Custom` ne génère **aucune barrière** dans `mount()` ; seuls les `EditRecord` ont `authorizeAccess()`.
   - **Solution** :
     ```php
     $this->record = $this->resolveRecord($record);
     abort_unless(auth()->user()?->can('enregistrerPresence', $this->record), 403);
     ```
     (idem `gererDecisions` pour la page décisions).
   - **Leçon** : Gate/policies et rendu Filament sont **indépendants** — toute page custom doit poser sa propre barrière.

9. **`Cannot redeclare non static Filament\Pages\Page::$view as static` (fatal PHP)**
   - **Cause** : `$view` est **non statique** dans `Filament\Pages\Page` ; la déclarer `static` dans la sous-classe est interdit.
   - **Solution** : `protected string $view = '...';` **sans** `static`.

10. **Double mail de convocation (fonctionnel)**
    - **Cause** : `NotificationService::sendReunionPlanifiee` envoyait `ReunionPlanifiee` (via() incluant `mail`) **ET** `Mail::queue(ReunionConvocation)`.
    - **Solution** : retirer `mail` de `via()` (garder `database`), `ReunionConvocation` = seul canal mail (vue markdown dédiée).
    - **Corollaire test** : `Notification::assertSentTo` **ne capture pas les Mailable** → `Mail::fake()` + `Mail::assertQueued(ReunionConvocation::class, 2)`.

11. **Page corbeille : signature `canAccess` incompatible (bloquant)**
    - **Cause** : (a) test sans `actingAs(admin)` → 302 ; (b) `canAccess(): bool` incompatible avec `Page::canAccess(array $parameters = []): bool`.
    - **Solution** : `actingAs(admin)` dans le test + signature `(array $parameters = [])`.

12. **Évidences écrites dans `private/private/evidence` (environnement)**
    - **Cause** : le disque `local` a pour racine `storage_path('app/private')` ; préfixe `private/` = dossier doublé.
    - **Solution** : `Storage::disk('local')->put('evidence/p6-*.pdf', ...)` et purge de `storage/app/private/private`.

13. **Limitation outil : pas d'inspection visuelle des PDF**
    - **Symptôme** : lecture PDF → `Cannot read pdf (this model does not support pdf input)`.
    - **Vérifs techniques effectuées** : fichiers **non chiffrés** (pas de `/Encrypt`, `%%EOF` valide), tailles cohérentes (PV 45 Ko / attestation 33 Ko), mêmes moteur mPDF que les évidences P4.
    - **Action humain requise** : confirmer le rendu en ouvrant `storage/app/private/evidence/p6-*.pdf` dans un lecteur PDF (Edge, Chrome, Adobe…). Le « charabia » dans Notepad = compression FlateDecode (normal).

---

## Anecdotes (résolues vite, P6)

- **`NOT NULL constraint failed: users.password`** : `User::firstOrCreate()` sans `password` → `updateOrCreate(['email' => ...], ['password' => bcrypt('password'), ...])`.
- **`Call to undefined method ...::factory()`** : factories existantes mais trait `HasFactory` absent sur `OdjTemplate`/`DecisionTemplate` → ajouter le trait.
- **Colonnes AuditLog dans le test** : le test requêtait `subject_type`/`subject_id`/`event` au lieu de `entity_type`/`entity_id`/`action` → aligner sur la vraie migration.
- **Nettoyage idempotent de la démo** : supprimer dans l'ordre des dépendances (`Etablissement` → `EcoleDoctorale` → `Universite`) pour éviter les FK ; conserver l'admin entre deux runs (unique `users.email`).