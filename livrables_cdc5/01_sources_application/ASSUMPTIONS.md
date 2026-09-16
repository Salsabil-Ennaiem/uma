# Registre des hypothèses — Plateforme UMA

Format : `ID · hypothèse · mécanisme de bascule · décideur · date`
Mis à jour à la fin de P8 (sept. 2026).

---

## Infrastructure / base de données

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H01 | SQLite est utilisé comme SGBD **en développement et en tests** ; il est **suffisant** pour valider la logique applicative (absence de contraintes FK dichotomiques,锁粒alité fine inutile en recette unitaire). | Changer `DB_*` dans `.env` → MySQL/PostgreSQL + appliquer `database/migrations` qui sont **déjà compatibles cross-SGBD** (pas de raw SQL dialectal). | Editeur / P9 | init |
| H02 | Le déploiement cible est l'infrastructure **CCK** (Centre de Calcul Khawarizmi) ; le choix de SQLite est donc temporaire. | Remplacer SQLite par **MySQL/MariaDB 8+** (stack CCK standard). | P9 recette | P1 |
| H03 | Le stockage des documents archivés se fait sur le **disque `public`** (`storage/app/public`) ; la visibilité publique est acceptable pour les PDFs d'attestation. | Déplacer vers un disque **privé** (`storage/app/private`) + policy de téléchargement authentifié, ou vers **S3/OBS** si CCK le propose. | P9 / éditeur | P7 |
| H04 | Les files de jobs/queues ne sont pas utilisées en production ; les Mailables/Notifications sont **envoyés de façon synchrone**. | Activer un **driver de queue** (database ou Redis) dans `.env` + config `queue.php` ; les classes `ShouldQueue` (`WorkflowTransitioned`, `ReunionPlanifiee`) sont déjà prêtes. | P9 | P8 |

---

## Sécurité / authentification

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H05 | `Gate::before` accorde **un accès super-admin** à l'admin ; c'est un raccourci Django/Filament **qu'il ne faut jamais compter dans la logique métier**. | Retirer `Gate::before` et gérer explicitement les permissions par policy (fail-closed partout). | P9 / admin | P5 |
| H06 | Le module de signature qualifiée **n'est pas encore branché** en production ; le driver `simple-image` est utilisé en recette (preuve P6). | Bascule en prod via `SIGNATURE_DRIVER=qualified` + config `pv-module.php` ; le `QualifiedSignatureStrategy` exige un **certificat PKCS#12** (à provisionner). | Éditeur / CCK | P5 |
| H07 | L'authentification se fait **uniquement par Filament login** (email + password) ; pas de SSO, pas de 2FA. | Intégrer **Laravel Fortify** (2FA) ou **SSO CCK** (LDAP/SAML) via un provider externe. | P9 / admin | P1 |
| H08 | L'utilisateur admin par défaut (`admin@uma.dz`) est créé par `make:filament-user` et n'est **jamais supprimé** en dev. | Audit de sécurité en prod : supprimer le compte par défaut, forcer la création via une procédure auditée. | P9 | P1 |

---

## Domaine métier

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H09 | La hiérarchie institutionnelle est **manuelle** (créée par l'admin via Filament) ; pas d'import automatique depuis inscription.tn. | Brancher une **importation CSV/XLSX** des structures universitaires/écoles/établissements (tâche P9). | Éditeur / P9 | P3 |
| H10 | Les **niveaux d'inscription** (1ʳᵉ→5ᵉ année) sont codés dans `annee_inscription` en texte libre ; pas de validation par un enum exhaustif. | Si validation nécessaire : créer un enum `NiveauInscription` et migrer `annee_inscription` vers des valeurs standardisées. | Éditeur | P8 |
| H11 | Un dossier de doctorat **appartient à un seul doctorant** (relation `BelongsTo doctorant`) ; pas de co-tutelle en base. | Ajouter une relation pivot `dossier_directeur` (ManyToMany) pour la cotutelle. | Éditeur / P9 | P8 |
| H12 | Une commission **gère une seule discipline** ; le même doctorant ne peut pas avoir de dossiers dans deux commissions différentes. | Si multi-discipline : transformer la relation en `BelongsToMany` avec pivot. | Éditeur | P1 |
| H13 | Les réclamations/tickets sont **uniquement texte** (type + contenu) ; pas de pièces jointes, pas d'arborescence de threads. | Ajouter une relation `documents()` polymorphique sur `Reclamation` pour les pièces jointes. | Éditeur | P8 |
| H14 | L'**ordre des transitions** est géré par la colonne `sort` (entier) ; pas de validation d'acyclicité du graphe de workflow. | Ajouter une vérification en base (trigger ou seeder guard) pour détecter les **cycles infinis** dans les workflows. | P8 / éditeur | P8 |

---

## Workflow (P8)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H15 | Les **gardes `contract`** (comme `CanManagePv::canCreate`) instancient une nouvelle instance via `app($contract)` ; le contrat est résolu par le container Laravel. | Si le contrat a un état partagé (singleton) : le hooker dans le container via `AppServiceProvider::register()`. | P8 | P8 |
| H16 | Les notifications de transition sont **role-based** (pas user-based) ; c'est-à-dire que le moteur notifie **tous les users d'un rôle donné** pour une transition donnée. | Si notification ciblée : passer la spécification `notifications` de `[{ "role": "doctorant", ... }]` à `[{ "user_id": 42, ... }]` + adapter `resolveRecipients`. | Éditeur | P8 |
| H17 | L'**actions de bord `reclamation.actualiser`** met à jour le `statut` de la réclamation **après** la transition ; c'est un contract implicite entre le moteur et le sujet métier. | Formaliser : créer un contrat `HasWorkflowStatus::syncFromInstance(WorkflowInstance $instance)` sur le sujet. | P9 | P8 |

---

## Module pv-module (package externe)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H18 | Le package `salsabil-ennaiem/pv-module` est **consommé tel quel** depuis Packagist ; l'application **ne le modifie jamais** (règle d'or du CDC). | Si besoin d'un fork : `composer config repositories.git https://github.com/...` + `composer require <fork>:dev-main`. | Éditeur | P3 |
| H19 | Le package **contrôle ses propres routes** (`admin/documents`) ; l'application ne peut pas les préfixer autrement que par `pv-module.php.routes.name_prefix`. | Changer le préfixe dans `config/pv-module.php` → `'name_prefix' => 'pv-module.'` (déjà fait, P3). | Éditeur | P3 |
| H20 | Les types de documents du CDC (`pv`, `attestation`, `decision`, `arrete`, `invitation`, `diplome`, `fiche_acces`) sont **fixés dans `config/archive.php`** et ne changent qu'en P4. | Si le CDC évolue : ajouter un type dans `config/archive.php` + migrer `rapport_etats.pv_type`. | Éditeur | P4 |

---

## Architecture / monolithe

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H21 | L'application est un **monolithe Laravel** (un seul artefact déployable) ; les « modules » (Réunions, Archivage, Workflows) sont des dossiers cohérents du monolithe, **pas des packages**. | Extraire chaque module en **package Composer interne** (voir §« Transformation vers modules interopérables »). | Éditeur / P10 | P1 |
| H22 | La migration vers un **module interopérable** est **différée après la recette** (P9) ; elle n'est pas un pré-requis au déploiement. | Planifier la transformation en P10/P11 (post-déploiement) comme projet d'amélioration continue. | Éditeur | P1 |
| H23 | Filament 5 est la **seule interface d'administration** ; il n'y a pas d'API REST/GraphQL pour des clients externes. | Si API nécessaire : créer des **Routes API** (`routes/api.php`) + resource transformers (Fractal/API Resources). | Éditeur | P1 |
| H24 | Les configurations (`pv-module.php`, `archive.php`, `uma.php`, `workflow.php`) sont versionnées **et** surchargées par `.env` si nécessaire. | Documenter chaque variable d'environment critique dans `.env.example`. | P9 | P1 |

---

## Déploiement / recette

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H25 | Le déploiement est prévu sur **CCK** (Centre de Calcul Khawarizmi) avec les normes **RNU** (Réseau National Universitaire). | Si hébergement externe : adapter la config mail/queue/cache/filesystems. | P9 | P1 |
| H26 | Les tests sont exécutés en SQLite **en CI/CD** (rapide, sans dépendance SGBD) ; la validation MySQL se fait **manuellement en recette**. | Ajouter un **service MySQL** en CI (Docker) pour des tests de compatibilité. | P9 | P7 |
| H27 | Aucun **IDOR** (Insecure Direct Object Reference) n'a été détecté en P7/P8 ; les policies Filament et les contrats RBAC bloquent les accès non autorisés. | Exécuter un **audit IDOR** complet en P9 (checklist dans `P9_recette_deploiement.md`). | P9 | P7 |
| H28 | Le fichier `.env` contient les secrets (APP_KEY, DB_PASSWORD, MAIL_PASSWORD) ; il n'est **jamais versionné**. | Si Cloud/CI : utiliser les **secrets du CI/CD** (GitHub Actions secrets, Laravel Cloud secrets). | P9 / déploy | P1 |

---

## UI / UX

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H29 | L'interface est **exclusivement en français** ; aucune gestion multilingue n'est prévue. | Si bilingue : activer `lang/` + config `app.locale` = `fr`, `app.fallback_locale` = `ar` + vues Blade avec `__('key')`. | Éditeur | P1 |
| H30 | Les **PDFs sont générés en mode portrait A4** par défaut ; le `RapportEtat.orientation` est surchargable par état. | Si besoins spécifiques : ajouter des formats papier (A3, Letter) dans `format_papier` du `RapportEtat`. | Éditeur | P7 |
| H31 | Les mises en page des emails (convocation, notification workflow) utilisent **Markdown** (`resources/views/emails/`) ; pas de template HTML riche. | Si besoin : créer des templates Blade HTML + `$html` dans `Content` du Mailable. | Éditeur | P6 |
