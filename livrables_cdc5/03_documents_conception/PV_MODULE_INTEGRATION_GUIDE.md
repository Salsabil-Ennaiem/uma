# Guide d'intégration et de mise à jour — `salsabil-ennaiem/pv-module`

> Ce document récapitule **toutes les commandes, modifications et correctifs** nécessaires pour :
> 1. Intégrer le paquet dans une **app Laravel neuve** (fresh install)
> 2. Mettre à jour le paquet dans une **app hôte existante** (ex. Voyager)
> 3. Comprendre les adaptations spécifiques à chaque hôte
>
> Dernière mise à jour : 2026-09-14 — bascule Packagist (v1.0.x, suppression du path repo dans l'hôte)

---

## 1. Prérequis

| Élément | Version |
|---|---|
| PHP | ≥ 8.2 |
| Laravel | ≥ 10.0 ≤ 13.x |
| mPDF | ^8.3 (inclus dans le paquet) |
| Base | SQLite (dev) ou MySQL (prod) |

Le paquet est **100% autonome** : aucune dépendance vers Voyager ou un hôte spécifique.

---

## 2. Intégration dans une app Laravel neuve (fresh install)

### 2.1 Créer l'app

```bash
# Depuis la racine du workspace (ex. stageNashd/)
laravel new pv-module-test
cd pv-module-test
```

### 2.2 Déclarer le paquet (Packagist)

Le paquet est publié sur Packagist (tags `v1.0.x` stables). **Plus besoin de repository path local.**

Éditer `composer.json` :

```jsonc
{
    "require": {
        "salsabil-ennaiem/pv-module": "^1.0"
    }
}
```

> L'ancien bloc `"repositories": [{ "type": "path", "url": "../voyager/packages/salsabil-ennaiem/pv-module" }]`
> a été retiré : la source de vérité est le **tag publié** (principe §3.1 de la roadmap), pas le working-tree local.

### 2.3 Installer le paquet

```bash
composer require salsabil-ennaiem/pv-module:^1.0
# ou, si déjà déclaré :
composer update salsabil-ennaiem/pv-module
```

Depuis `v1.0.x` (tag stable publié), la contrainte `^1.0` suffit — plus besoin de `"*@dev"`.
Pour suivre la branche de développement, utiliser explicitement le repository Git :

```jsonc
{
    "require": { "salsabil-ennaiem/pv-module": "dev-master" },
    "repositories": [{ "type": "vcs", "url": "https://github.com/Salsabil-Ennaiem/pv-module.git" }]
}
```

### 2.4 Créer les stubs login/logout (obligatoire si l'app n'a pas Fortify/UI)

L'app neuve n'a pas de route `login` — le middleware `auth` redirige vers `route('login')` → `RouteNotFoundException`.

Éditer `routes/web.php` de l'app hôte :

```php
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');
```

### 2.5 Migrer la table notifications

La table `notifications` est requise par le canal database de Laravel pour les notifications `PvValidationRequest`.

```bash
php artisan notifications:table
php artisan migrate
```

### 2.6 Configurer la base SQLite

```bash
touch database/database.sqlite
php artisan migrate --force
```

### 2.7 Vérifier l'installation

```bash
php artisan route:list --path=pv-module
php artisan test --filter PvModuleInFreshAppTest
```

**Résultat attendu** : 10/10 tests (29 assertions) — ✅

---

## 3. Mise à jour du paquet dans un hôte existant (Voyager)

### 3.1 Mettre à jour le code du paquet

Depuis la bascule Packagist, **plus de junction/symlink** : Voyager consomme la distribution dist (v1.0.x) dans
`vendor/salsabil-ennaiem/pv-module`. Le dossier `packages/salsabil-ennaiem/pv-module` reste le dépôt Git autonome
(source de vérité), mais n'influence plus directement l'app.

Pour livrer une modification dans l'hôte :

1. Committer dans `packages/salsabil-ennaiem/pv-module` (dépôt + remote `origin` github).
2. Créer un tag sémantique (`git tag v1.0.x`) et le pousser : `git push origin v1.0.x`.
3. Dans l'hôte : `composer update salsabil-ennaiem/pv-module` → le tag prime sur le working-tree local.

### 3.2 Redécouvrir le package (si nouveau provider ou config)

```bash
php artisan package:discover
php artisan config:clear
```

### 3.3 Publier les traductions (nouveau depuis la refonte)

```bash
php artisan vendor:publish --tag=pv-module-lang --force
```

Ceci crée `lang/{fr,en,ar}.json` dans l'app hôte.

### 3.4 Exécuter les migrations (si ajout de colonnes/tables)

```bash
php artisan migrate --force
```

### 3.5 Vérifier la régression

```bash
php artisan test --filter PvWorkflowTest
```

**Résultat attendu** : 18/18 tests (59 assertions) — ✅

---

## 4. Fichiers modifiés lors de la refonte UI (Phase 11)

### 4.1 Traduction & lang

| Fichier | Modification |
|---|---|
| `src/PvModuleServiceProvider.php` | Ajout `loadJsonTranslationsFrom()` + publish lang |
| `resources/lang/fr.json` | Créé — ~200 clés FR |
| `resources/lang/ar.json` | Créé — ~200 clés AR |
| `resources/lang/en.json` | Créé — ~200 clés EN (toutes les langues partagent les mêmes clés) |

### 4.2 Design System

| Fichier | Modification |
|---|---|
| `resources/views/layouts/app.blade.php` | Réécrit : variables CSS `--pvm-*`, nav responsive, cards, badges, alertes, boutons, tables, forms, canvas, breadcrumb, timeline, progress, tabs, RTL + print |

### 4.3 Partiels

| Fichier | Rôle |
|---|---|
| `resources/views/partials/breadcrumb.blade.php` | Fil d'Ariane réutilisable |
| `resources/views/partials/status-badge.blade.php` | Badge statut coloré |

### 4.4 Vues PV refondues

| Fichier | Modifications |
|---|---|
| `resources/views/pv/index.blade.php` | Filtres q/statut/from/to/sort, pagination, compteurs par statut, état vide filtré |
| `resources/views/pv/create.blade.php` | Design system, flash messages |
| `resources/views/pv/edit.blade.php` | Design system, rétroédition |
| `resources/views/pv/show.blade.php` | Timeline, badge statut, progression validations |
| `resources/views/pv/preview.blade.php` | Design system |
| `resources/views/pv/versions.blade.php` | Design system |

### 4.5 Contrôleurs

| Fichier | Modifications |
|---|---|
| `src/Http/Controllers/PvController.php` | `index()`: filtres, compteurs, flash `__()` ; méthode privée `visibleQuery()` ; `notifications()`, `readNotification()`, `readAllNotifications()` |
| `src/Http/Controllers/TemplateController.php` | **Créé** : index/create/edit/update/destroy/reset + garde `canManageTemplates` via `method_exists()` |
| `src/Contracts/CanManagePv.php` | **Sans modification** — pas de méthode `canManageTemplates` dans l'interface (interdit en PHP) |
| `src/Defaults/DefaultPvRules.php` | Ajout `canManageTemplates()` |

### 4.6 Routes

| Fichier | Modification |
|---|---|
| `routes/web.php` | Ajout groupe `templates/*` **AVANT** les wildcards `{pv}` (first-match-wins) |

### 4.7 Templates

| Fichier | Rôle |
|---|---|
| `resources/views/templates/index.blade.php` | **Créé** : liste modèles défaut + customisés |
| `resources/views/templates/edit.blade.php` | **Créé** : éditeur avec flèches ↑↓, orient., marges, styles par section |

### 4.8 PDF

| Fichier | Modification |
|---|---|
| `resources/views/pdfs/pv_template.blade.php` | Libellés traduits (« Généré le », « par », « Délai ») ; **alignement par section** (`text-align` sur titre + corps) |

### 4.9 Signature

| Fichier | Modification |
|---|---|
| `resources/views/signature/index.blade.php` | Refondu : canvas + fetch save, design system |

### 4.10 Améliorations éditeur de templates (UI 2)

| Fichier | Modification |
|---|---|
| `resources/views/templates/edit.blade.php` | **Drag & drop natif** (HTML5) pour réordonner les sections (en plus des flèches ↑/↓), **menu Alignement** (Gauche/Centre/Droite) par section, **sections repliables** : clic sur le titre OU bouton « Voir »→« Masquer » |
| `src/Http/Controllers/TemplateController.php` | Validation `sections.*.textAlign` (`in:left,center,right`) + persistance dans `styles.textAlign` |
| `resources/lang/{fr,en,ar}.json` | Nouvelles clés : `Alignement`, `Centre`, `Masquer`, `Monter`, `Descendre`, `Glisser pour réordonner` |

---

## 5. Contraintes techniques connues

### 5.1 Interface PHP et corps de méthode

**FAUX** : PHP 8.0+ autorise les méthodes avec corps dans les interfaces.
**VRAI** : `Fatal error: Interface function cannot contain body` — vérifier avec `php -l`.

Notre solution : `canManageTemplates()` n'est PAS dans `CanManagePv`. Le contrôleur la teste via `method_exists()` — l'hôte peut l'ajouter à sa classe de règles sans casser le contrat.

### 5.2 Ordre des routes

Les routes `templates/*`, `signature/*` et `notifications/*` doivent être déclarées **AVANT** `{pv}` dans `routes/web.php` — Laravel utilise le premier match.

### 5.2bis Notifications database

La cloche et la page `notifications/index.blade.php` utilisent le canal `database` de Laravel. Elles sont conditionnées par `method_exists($user, 'unreadNotifications')` + `try/catch` : sur un hôte sans table `notifications`, la page `index` et le bouton « Tout marquer lu » ne cassent pas le module.

### 5.3 User model — accessor vs colonne

- **Voyager** : User a un accessor `nom`/`prenom` (pas de colonne `name`) → `getNomCompletAttribute()`
- **App neuve** : User a une colonne `name` standard

`PvController::allUsers()` utilise `sortBy()` en mémoire (pas SQL) pour supporter les deux.

### 5.4 Cast Eloquent — config templates

`PvTemplate` utilise `'config' => 'array'` → Eloquent gère la sérialisation JSON.
Pas besoin de `json_encode/decode` manuel dans les contrôleurs.

### 5.5 Closure::count()

Ne PAS utiliser `(clone $closure)->count()` — une closure n'a pas de méthode `count()`.
Utiliser une méthode privée qui retourne un Builder (`visibleQuery()`).

### 5.6()->values() sur array

`$array->values()->all()` → erreur si `$array` est déjà un array PHP.
Vérifier le type avant d'appeler des méthodes Collection.

---

## 6. Tests

### 6.1 Côté Voyager (hôte)

```bash
# Tests workflow complet
php artisan test --filter PvWorkflowTest
# Attendu : 18/18 (59 assertions)

# Tests UI (filtres, templates, 403)
php artisan test --filter PvModuleUiTest
# Attendu : 6/6 (36 assertions)
```

### 6.2 Côté app neuve

```bash
cd pv-module-test
php artisan test --filter PvModuleInFreshAppTest
# Attendu : 10/10 (29 assertions)
```

### 6.3 Vérification syntaxe (à chaque modification)

```bash
php -l packages/salsabil-ennaiem/pv-module/src/PvModuleServiceProvider.php
php -l packages/salsabil-ennaiem/pv-module/src/Http/Controllers/PvController.php
php -l packages/salsabil-ennaiem/pv-module/src/Http/Controllers/TemplateController.php
```

---

## 7. Commandes速查 (quick reference)

| Action | Commande |
|---|---|
| Installer le paquet | `composer update salsabil-ennaiem/pv-module` (après ajout path repo) |
| Redécouvrir | `php artisan package:discover` |
| Publier langues | `php artisan vendor:publish --tag=pv-module-lang --force` |
| Migrer | `php artisan migrate --force` |
| Tests Voyager | `php artisan test --filter PvWorkflowTest` |
| Tests UI | `php artisan test --filter PvModuleUiTest` |
| Tests fresh app | `cd pv-module-test && php artisan test --filter PvModuleInFreshAppTest` |
| Syntaxe PHP | `php -l <fichier>` |
| Config clear | `php artisan config:clear` |

---

## 8. Historique des correctifs (P14-P19 + Phase 11)

| Correctif | Problème | Solution |
|---|---|---|
| P14 | User Voyager sans colonne `name` | `sortBy()` en mémoire sur accessor |
| P15 | Signature stream pas	ReturnType | `header()` + `readfile()` + `exit` |
| P16 | Conflit routes `{pv}` vs `signature/*` | Ordre des routes (statiques avant wildcards) |
| P17 | Banner signature masquée | Style inline `z-index:100;position:relative` |
| P18 | Bouton retour signature | Lien back ajouté |
| P19 | Traduction FR/AR incomplete | 200+ clés ajoutées |
| UI-1 | `Closure::count()` | Méthode `visibleQuery()` privée |
| UI-2 | `values()->all()` sur array | Simplifié en `values()` |
| UI-3 | Test 403 templates (singleton) | Fixture nommée + config override |
| UI-4 | État vide filtres | Condition `! $filters` au lieu de `total()===0` |
| UI2-1 | Pas de drag & drop sections | Drag & drop natif HTML5 (handle ⠿) conservant les flèches |
| UI2-2 | Pas d'alignement de texte | Champ `textAlign` par section (Gauche/Centre/Droite) + rendu PDF |
| UI2-3 | Sections toujours dépliées | Toggle Voir/Masquer : clic sur titre ou bouton `data-toggle-type` |
| UI3-1 | Filtres avec bouton « Envoyer » | Auto-soumission après chaque changement (`data-pvm-auto`, debounce 350 ms) |
| UI3-2 | Pas de bouton « Modifier » dans la liste | Ajouté dans la colonne Actions (owner + statut ≠ valide) |
| UI3-3 | Double-clic création PV | Boutons désactivés + « Traitement… » lors du submit (create + edit) |
| UI3-4 | Drag & drop inactif | `draggable="true"` sur le handle ⠿ (le drag se fait uniquement par la poignée) |
| UI3-5 | Pas d'ajout/suppression de section | Bouton « + Ajouter une section » + « Supprimer » par section (fixes protégées côté serveur) |
| UI3-6 | Statut « valide » affichant un « ✕ » rouge | Badge ✕ masqué si `statut === valide` ; « délai dépassé » renommé « Signature en retard » (ambre) |
| P20 | Statut « brouillon » dans l'historique des versions | `Pv::recordVersion(?statut)` appelé à chaque transition (envoi → en_attente, validation → valide, rejet → rejete) dans `PvService::sendToParticipants()` et `PvValidation` |
| P21 | Label « Rejeté » sur toutes les lignes de la liste | Fuite de scope Blade : variable de boucle des onglets renommée `$tabLabel` (l'`@include` hérite du scope parent) |
| P22 | `duplicateFromChanges(): Argument #3 ($templateData) must be of type array, null given` | Cast `(array) ($data['template_data'] ?? $pv->template_data ?? [])` dans `PvService::update()` |
| P23 | Sections fixes (header/middle/signature) déplaçables + alignables | Masquées dans l'éditeur : pas de drag handle, flèches, suppression, alignement ; gardes `data-fixed` dans le JS |
| P24 | Table participants du PDF ignorait l'alignement de section | `$textAlignCss($style)` appliqué aux th/td du tableau participants |
| P25 | Pas de page/cloche notifications | Routes `notifications/*` AVANT les wildcards + `PvController::notifications/readNotification/readAllNotifications` + vue `notifications/index.blade.php` + cloche dans le layout (garde `try/catch` si table absente) |
| P26 | 403 après validation alors que la réponse est enregistrée | `canDownload` = manager OU participant (`isParticipant()` : toute ligne de validation existe) |
| P27 | Signer sans signature : aucune erreur visible | Alerte `$errors` en haut de `show.blade.php` + bandeau avec flèche « Pour signer, vous devez d'abord enregistrer votre signature » → « Ma signature » |
| P28 | Bouton « Modifier » basé sur `created_by` | `$rules->canUpdate($actor, $pv)` dans `index` et `show` (show : `&& statut !== valide`) |
| P29 | Délai dépassé visible seulement en attente + pas de choix de colonnes | Badge « Délai dépassé » pour tous statuts ; sélecteur de colonnes persisté en `localStorage` (`pvm_columns`, `.pvm-col-hidden`, `data-col`) |
| P30 | `Undefined array key "type"` dans le panneau cloche / page notifications | `$type = $data['type'] ?? '';` avant les comparaisons ; deux vues corrigées |
| P31 | Boutons d'action séparés en haut de `show` | Regroupés dans un **speed dial** (FAB flottant avec liste d'actions, CSS/JS dédiés dans le layout) |
| P32 | Sélecteur de colonnes en ligne « Colonnes affichées » | Déplacé dans un **menu hamburger** (☰) — les cases restent persistées (`.pvm-col-toggle`) |
| P33 | Colonne « Décisions incluses » peu claire + statut historique « Brouillon » | Renommage **Rubriques** (`N rubrique(s)`, filtrage des vides) ; ligne la plus récente de l'historique affiche le **statut actuel du PV** + note explicative |
| P34 | Actions liste « Mes PV » encore séparées + hamburger colonnes hors ligne de titre | **Menu compact ⋮ par ligne** (toujours affiché : Voir / Modifier / PDF) + **hamburger ⚙️/☰ intégré à la ligne des titres de colonnes** (colonne « Colonnes », non masquable) |

## 9. Intégration « UI 2 » côté hôtes

Après avoir modifié le paquet (drag & drop, alignement, toggle) :

```bash
# Voyager — les vues sont servies depuis le paquet (junction) : rien à publier
php artisan test --filter PvWorkflowTest          # 12/12 attendu
php artisan test --filter PvModuleUiTest          # 5/5 (24 assertions) attendu

# App neuve — le paquet est aussi en junction depuis ../voyager : rien à publier
cd pv-module-test
php artisan test --filter PvModuleInFreshAppTest  # 10/10 attendu
```

> Note : les clés `textAlign` sont stockées dans `config['sections'][*]['styles']['textAlign']`.
> Si tu publies les langues (`vendor:publish --tag=pv-module-lang --force`), le fichier `lang/ar.json`
> de l'hôte ne contiendra PAS les nouvelles clés — republier force avec la commande ci-dessus.

## 10. Installer et utiliser le paquet SANS le projet local

Le path repo ne marche que sur ta machine. Pour qu'une **autre personne** intègre `salsabil-ennaiem/pv-module` (ou pour le CI), voici le parcours complet — de l'installation à la personnalisation (UI, types de documents, ou backend seul).

### 10.1 Installer le paquet (deux options)

**Option A — Packagist (recommandé, toute autre personne / CI)** : le paquet est publié et **tagué** (`v1.0.x`), le tag passe le `minimum-stability: stable`.

```bash
composer require salsabil-ennaiem/pv-module:^1.0
```

**Option B — Path local (développement, mono-repo)** :

```jsonc
{
    "repositories": [
        { "type": "path", "url": "../voyager/packages/salsabil-ennaiem/pv-module", "options": { "symlink": true } }
    ],
    "require": { "salsabil-ennaiem/pv-module": "^1.0" }
}
```

- Packagist taggé (`v1.0.x`) : plus rien à ajouter, `composer require salsabil-ennaiem/pv-module:^1.0` suffit (le `dev` s'efface : le tag passe le `minimum-stability: stable`).
- Pour suivre la branche par défaut du dépôt GitHub : ajouter `{ "type": "vcs", "url": "https://github.com/Salsabil-Ennaiem/pv-module.git" }` dans `repositories` puis `composer require salsabil-ennaiem/pv-module:dev-master`.
- Le `dev` = **version de développement de la branche par défaut, sans plancher** ; il n'est nécessaire que pour les branches non taguées.

### 10.2 Après l'installation, dans l'app hôte

```bash
php artisan vendor:publish --tag=pv-module-config --force
php artisan vendor:publish --tag=pv-module-lang    --force   # fr/en/ar
php artisan migrate
```

Le provider `PvModuleServiceProvider` se découvre automatiquement (composer) ; sinon l'ajouter à `bootstrap/providers.php` :
`SalsabilEnnaiem\PvModule\PvModuleServiceProvider::class`.

Régler le **modèle utilisateur** dans `config/pv-module.php` :

```php
'user_model' => \App\Models\User::class,   // DOIT être le modèle de l'hôte, jamais un modèle du module
```

L'hôte doit fournir les **stubs login/logout** (cf. §2.4) si l'app n'a pas Fortify/UI, et le **modèle `User` doit avoir** :
- une colonne `name` (ou `nom`/`prenom` — le module lit `name` en priorité, avec repli sur `nom`),
- la relation `notifications()` de Laravel (trait `Notifiable`) pour la cloche et `Foo::notify()`.

### 10.3 Personnaliser l'UI (vues, langues, assets) SANS toucher au paquet

Les vues sont chargées via le namespace `pv-module::`. Pour les **surdéfinir côté hôte**, publier puis éditer :

```bash
php artisan vendor:publish --tag=pv-module-views --force
php artisan vendor:publish --tag=pv-module-assets --force
```

- Les vues publiées atterrissent dans `resources/views/vendor/pv-module/` (même arborescence : `pv/`, `templates/`, `partials/`, `layouts/`).
- Les assets JS (speed dial, hamburger, colonnes) dans `public/vendor/pv-module/`.
- Depuis le hôte, on peut aussi **étendre** une vue du paquet sans la copier (cf. §9 : rien à publier pour Voyager, junction `../voyager`).

Améliorations récentes de l'UI à connaître :
- **Speed dial** sur la fiche PV (bouton `+` flottant en bas à droite : Modifier / Aperçu / PDF / Versions) et **menu compact ⋮ par ligne** dans la liste « Mes PV » ;
- **Hamburger ⚙️ sur la ligne des titres de colonnes** de la liste : choisis les colonnes affichées (Titre, Statut, Délai, Date, Validations) — persisté en `localStorage` ;
- **Colonne « Rubriques »** dans l'historique des versions : nombre de sections (`contenu`) non vides pour cette version ;
- Statut **actuel** affiché sur la ligne la plus récente de l'historique (les snapshots `versions[].statut` sont pris à l'enregistrement).

### 10.4 Utiliser le module pour d'AUTRES types de documents

Le module est un **moteur de workflow** : créer/trier/filtrer/signer/valider/versionner. Pour un autre type de document (contrat, rapport, décision, bon de commande…) :

1. **Déclarer le type** dans `config/pv-module.php` : `'types' => ['pv', 'contrat']`. Le champ `data_type`/`type` du PV porte le type ; les routes gardent un préfixe commun.
2. **Nouvelles colonnes/rubriques** : le contenu est un **tableau `contenu` de rubriques** (libre). Construire un template adapté via `PvTemplate` (sections avec `title`, styles `titleColor`/`titleSize`/`textSize`/`textAlign`) et le seed default contient les rubriques du nouveau type. La colonne « Rubriques » compte ces sections automatiquement.
3. **Contrat / rôles / acteurs par type** : chaque type peut avoir ses propres classes de règles (voir §10.5) — ex. pour un contrat : créateur = émetteur, signataires = contractants, délai = date de signature du contrat.
4. **Créer un PV d'un autre type** : `Pv::create(['type' => 'contrat', 'contenu' => [...], ...])` ; le workflow (send/validate/sign/reject, versions, PDF) est **inchangé**.

### 10.5 Quand NE PAS toucher aux vues : personnaliser par le BACKEND seul

Le code travaille via **contrats** (interfaces) — on peut modifier règles et comportement **sans toucher une seule vue** :

| Contrat (`SalsabilEnnaiem\PvModule\Contracts`) | Rôle | Implémentation par défaut |
|---|---|---|
| `CanManagePv` | `canCreate/canUpdate/canSend/canValidate/canSign/canDelete/canDownload` | `DefaultPvRules` (propriétaire = créateur) |
| `ApprovalRules` | seuils (unanimité, quorum, délai) | `DefaultApprovalRules` |
| `ParticipantResolver` | qui reçoit la demande de signature (`send`) | `DefaultParticipantResolver` |

**Implémenter une interface** dans l'app hôte (ex. RBAC : seuls les resp. de pôle créent, les managers valident) puis l'enregistrer :

```php
// config/pv-module.php
'can_manage_pv'        => \App\PvRules\MyPvRules::class,
'approval_rules'       => \App\PvRules\MyApprovalRules::class,
'participant_resolver' => \App\PvRules\MyParticipantResolver::class,
```

Le provider bind les singletons (`config('pv-module.can_manage_pv')` etc.) → **zéro modification du paquet**. C'est le bon chemin quand : l'app hôte a sa propre RBAC, des seuils de validation métier, ou un annuaire d'acteurs externe.

**Contexte de modification (`canUpdate`)** : par défaut, **seul le créateur** (manager) peut modifier → `DefaultPvRules::canUpdate = isManager = created_by === $actor->getKey()`. L'utilisateur ordinaire (signataire/participant) ne voit jamais le bouton « Modifier » ; il ne peut que **valider / signer / rejeter** sur la fiche PV (bloc « Valider ce PV ») et, pour les acteurs, la demande de signature part de `send` (chemin « Envoyer aux participants »).

### 10.6 Le `*@dev` côté consommateur

- `dev` = **stabilité dev** : autorise les versions non-taguées (`dev-master`/`dev-main`).
- `*@dev` = **version de la branche dev par défaut**, sans plancher.
- C'est uniquement nécessaire tant que le paquet n'a pas de tag stable. Dès `v1.0.0`, `^1.0` suffit.
