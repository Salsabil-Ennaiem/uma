# Bugs & problèmes rencontrés — Plateforme UMA (par étape : P1, P3, P4, P5, P6, P7)

> Seuls les **vrais bugs / obstacles** (bloquants ou subtils) sont listés, avec cause racine et solution.
> P1/P3/P4/P5 : reconstitués d'après le README (sessions antérieures). P6/P7 : sessions actuelles (exacts).
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

---

## P7 — Archivage & audit (SESSION ACTUELLE — bugs réels)

14. **`Unparenthesized a ? b : c ?: d` (Fatal PHP 8.5, bloquant)**
    - **Cause** : ternaire chaînée `a ? b : c ?: d` non parenthésée dans `ArchiveService.php:137` — PHP 8.5 en fait une **Erreur fatale**.
    - **Solution** : extraire `$ext` dans une variable intermédiaire avant la ternaire.
    - **Leçon** : toute chaîne `? :` suivie d'un `?:` doit être parenthésée (`a ? b : (c ?: d)`), ou refactorisée.

15. **`ParseError: Unclosed '{'` dans les RelationManagers Filament (bloquant)**
    - **Cause** : **arrow functions** imbriquées dans les closures Filament (`fn (...) => ...`) ; combinaison avec les ternaires/PHP 8.5 sous Windows → erreur de parsing cryptique sans indication de ligne.
    - **Solution** : réécrire `DocumentsRelationManager` et `DecisionsRelationManager` avec des **méthodes helper** classiques (`typesOptions()`, `toUploadedFile()`) — plus aucune arrow fn.
    - **Leçon** : en PHP 8.5/Windows, privilégier les closures/méthodes classiques dans les schémas Filament ; on perd du temps à débouger un ParseError sinon.

16. **`DecisionsRelationManager` : `$title` typé `string` alors que Filament attend `?string`**
    - **Cause** : contrat de `Select::createOption` déclaré `?string`, notre closure `string $title` → TypeError à l'exécution.
    - **Solution** : `?string $title`.

17. **`Document::lastVersion()` retournait v1 au lieu de v2 (SQLite, bloquant)**
    - **Cause** : `latest('version')` recalait **encore** `ORDER BY version` (le HasMany pose déjà `orderBy('version', 'asc')`), annulant le tri décroissant ; le dataset de test revenait à v1.
    - **Solution** : `$this->versions()->reorder('version', 'desc')->first()` — `reorder()` écrase proprement l'ordre du relation.
    - **Leçon** : `latest()` sur une relation déjà ordonnée est trompeur ; `reorder()` explicite.

18. **`DocumentResource` : collision de route avec le pv-module (`admin/documents`)**
    - **Cause** : le package occupe déjà `admin/documents` (immutable) ; Filament tentait de re-déclarer la route → conflit.
    - **Solution** : `protected static ?string $slug = 'mallette';` → `admin/mallette`, navigation « Archivage & audit ».
    - **Leçon** : vérifier les slugs du package **avant** de `--generate` une ressource du même nom.

19. **`Controller` de base : `Call to undefined method ...::authorize()` (bloquant)**
    - **Cause** : `RapportController::apercu()`/`pdf()` appellent `$this->authorize('generer', ...)` mais le `Controller` de base ne charge pas `AuthorizesRequests`.
    - **Solution** : sur `app/Http/Controllers/Controller.php`, `use AuthorizesRequests, ValidatesRequests;` (compatibilité apps « classiques »).

20. **Page `/admin/rapport-etats` : `Method Filament\Tables\Columns\TextColumn::boolean does not exist` (500, bloquant)**
    - **Cause** : `TextColumn::make('is_active')->boolean()` — en Filament 5.8, `boolean()` n'existe plus sur `TextColumn` (déplacé sur `IconColumn`).
    - **Solution** : `IconColumn::make('is_active')->boolean()` (convention déjà en place dans `CommissionResource`/`DecisionTemplateResource`), import ajouté.
    - **Leçon** : aligner toute nouvelle colonne booléenne sur le pattern `IconColumn` ; add un **smoke test Filament** (`filament.admin.resources.rapport-etats.index/create/edit` → 200) pour Cacher ce type de régression.

## P8 — Moteur de workflow paramétrable (SESSION ACTUELLE — bugs réels)

> **Note** : les résolutions P8 ont été corrigées **pendant la session en cours** (pas de commit à l''avance pour séparer cause/solution). Leur compteur suit le dernier P7 (bug #20).

---

21. **`SQLSTATE: index workflow_instances_subject_type_subject_id_index already exists` (échec de migrate, bloquant)**
    - **Cause** : la migration utilisait `$table->morphs('subject')` **ET** un `$table->index(['subject_type', 'subject_id'])` supplémentaire. Or `morphs()` crée déjà cet index → tentative de créer un 2ᵉ index homonyme → SQLite rejette.
    - **Solution** : supprimer la ligne `$table->index(...)` de la migration (doublon strictement inutile).
    - **Leçon** : `morphs()` équivaut à `string() + id() + index` — ne jamais redéclarer l''index séparément.

22. **Mise à jour partielle de la base après échec de migration (corrompue, bloquant)**
    - **Cause** : la migration a créé les premières tables (`workflow_definitions`…) avant d''échouer sur l''index ; le relancer au 2ᵉ essaie échoue puisque les tables existent déjà.
    - **Solution** : exécuter un script PHP externe (`drop_partial_tables.php`) appelant `Schema::dropIfExists()` sur chaque table partielle, puis relancer `php artisan migrate` proprement. Le script a été supprimé après usage.
    - **Leçon** : en SQLite, un échec partiel de migration laisse l''état corrompu — toujours vérifier `Schema::hasTable()` avant `create`, ou purger avant réessayer.

23. **`WorkflowGuard::syncGuards` dupliquait les gardes à chaque relance du seeder (fonctionnel, subtil)**
    - **Cause** : la clé d''idempotence était `$rule.':'.json_encode($params)` comparée à un simple `pluck('rule')` (sans les params). `json_encode` de params identiques = même clé, mais la colonne `rule` seule ≠ clé complète → toujours considéré comme nouveau → doublon.
    - **Solution** : mapper les gardes existants sur la clé complète `$rule.':'.json_encode($params)` avant de comparer (comme fait en `syncGuards`).
    - **Leçon** : l''idempotence d''un seeder qui crée des données relationnelles complexes doit comparer **toute la clé métier**, pas une colonne partielle.

24. **`RuntimeException: No instances [App\Services\AuditLogger]` au démarrage du moteur (bloquant)**
    - **Cause** : `WorkflowEngine` déclare un `private AuditLogger $audit` en injected constructor ; or `AuditLogger` n''est pas bind dans le container et n''a pas de `__construct()` résolu automatiquement. Laravel ne sait pas le résoudre.
    - **Solution** : dans `WorkflowEngine.__construct()`, injecter le service `App\Services\AuditLogger` — vérifier que le fichier existe bien (`app/Services/AuditLogger.php`, créé en P7). L''erreur est apparue en test car le `app()` n''avait pas encore bootstrappé correctement ; résolu en recréant la methode `make` ou en instanciant proprement dans le constructor du test.
    - **Leçon** : lorsqu''un service dépend d''un autre service, les deux doivent être **résoluble via le container**. Test d''abord avec `php artisan tinker --execute="dd(app(WorkflowEngine::class));"`.

25. **`Rôle « agent_administration » non autorisé pour la transition valider_depot` — rôle déclaré dans le seeder mais refusé par le moteur (bloquant, subtil)**
    - **Cause** : dans `WorkflowEngine::guard()`, la variable `$role` contient **l''instance de l''enum `UserRole`** (`$actor->role`), mais `$allowedRoles` contient des **chaînes** (`['agent_administration', …]`). La comparaison stricte `in_array($enumInstance, $strings, true)` est **toujours false** → tous les rôles déclarés échouent.
    - **Solution** : comparer `$role?->value` (la chaîne) plutôt que l''instance enum elle-même :
      ```php
      $roleValue = $role?->value ?? null;
      // …
      $roleValue === UserRole::Admin->value || in_array($roleValue, $allowedRoles, true)
      ```
    - **Leçon** : PHP 8.1+ casts les enums-backed via `$model->role` → instance enum. `in_array` strict comparing enum ≠ string — **toujours extraire `->value`** avant une comparaison array.

26. **Réclamation : `statut` et `closed_at` non mis à jour par le moteur (fonctionnel, subtil)**
    - **Cause** : le moteur applique les actions déclarées en base (`reclamation.actualiser`) **avant** d''avoir mis à jour `$instance->current_state` vers le nouvel état. L''action lisait l''ancien état (`ouverte`) au lieu du nouveau (`en_cours`, `cloturee`) → le `statut` de la réclamation restait à sa valeur initiale.
    - **Solution** : dans `WorkflowEngine::apply()`, définir `$instance->current_state = $to` **avant** la boucle des actions, puis appeler `$instance->save()` après — les actions voient maintenant le bon état et le `statut` se met à jour correctement.
    - **Leçon** : l''ordre dans un workflow est **crucial** → première_phase (guard), deuxième_phase (écriture état), troisième_phase (actions qui lisent l''état), quatrième_phase (persistence). Ne pas mélanger les étapes.

27. **`creerReservation` ne trouvait pas les dates de soutenance (fonctionnel)**
    - **Cause** : les gardes (`no_overlap`) lisent `$instance->data` qui ne contenait **pas encore le payload** (merge effectué après les gardes et les actions). Le payload (`soutenance_date`, `salle`, `jury_membres`) n''était pas accessible dans les actions.
    - **Solution** : fusionner `$payload` dans `$instance->data` **avant** d''exécuter les gardes **et** les actions (le merge a lieu dès le début du `DB::transaction`, juste après la validation).
    - **Leçon** : le payload d''une transition doit être **disponible** à la fois pour les gardes qui l''évaluent et pour les actions qui l''utilisent — le merge doit précéder les deux.

28. **Duplication de `.gitkeep` inutile dans les dossiers remplis (vestige, non bloquant)**
    - **Cause** : les fichiers `.gitkeep` (créés au moment où les dossiers étaient vides — PvRules, Services, Reunions, Commissions) n''ont jamais été supprimés une fois les dossiers remplis.
    - **Solution** : documentation dans `docs/structure-du-projet.md` + signalés pour suppression au prochain commit. Les fichiers restent **inoffensifs** tant qu''ils existent.
    - **Leçon** : un `.gitkeep` est une **solution de contournement temporaire** — penser à le supprimer dès que le dossier contient au moins un fichier réel.
