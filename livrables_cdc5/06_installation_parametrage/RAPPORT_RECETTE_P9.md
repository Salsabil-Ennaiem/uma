# Rapport de recette — P9 (Livrable CDC §5 / 06)

> **Mission** : P9 — Recette, imports, durcissement, déploiement (Étape 7 du plan d'intégration).
> **Statut** : CLOTURE — recette favorable.
> **Date du rapport final** : 2026-09-15.
> **Rapport consolidé** à partir de `P9_recette_deploiement.md` après re-exécution complète
> de la suite sur un environnement propre (cache de configuration purgé).

---

## 1. Objet et périmètre

Recette de l'application **uma** (plateforme UMA de gestion des réunions de soutenance).
Couvre : conformité CDC §6 (grilles, filtres, exports, emails) via **Filament natifs**,
**imports en masse** (validate-then-commit), **revue sécurité**, **UAT commission pilote**,
**tests automatisés finaux**, et le **dossier de déploiement CCK/RNU** (document frère).

Aucune modification du package `salsabil-ennaiem/pv-module` n'est intervenue (`^1.0`, vériﬁée à `v1.0.2`).

## 2. Environnement de recette

| Élément | Valeur |
|---|---|
| PHP | `^8.3` |
| Framework | Laravel `^13.17` (vériﬁé `13.31`) |
| Panneau admin | Filament `^5.0` |
| Package documentaire | `salsabil-ennaiem/pv-module ^1.0` (lock `v1.0.2`) |
| Tests | Pest `^5.1` |
| Base locale | SQLite `phpunit.xml` (`:memory:`), MySQL 8 en cible prod |
| Drivers recette | `SESSION_DRIVER=array`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array` |
| Commande canonique | `composer test` (purge le cache de config puis `php artisan test`) |

**Point de méthode** : la suite doit être lancée **après purge du cache de configuration**
(`php artisan config:clear`). Avec `bootstrap/cache/config.php` existant, les variables
`<env>` de `phpunit.xml` sont ignorées (l'app ﬁgée sur `.env`), ce qui produisait 14 faux
échecs CSRF (419) et des `assertQueued` à 0. Aucune modification du code applicatif n'a été
nécessaire — seul le recalage d'environnement.

## 3. Résultats des tests automatisés

Exécution `2026-09-15`, config purgée, base `:memory:` :

> **87 passed / 87 — 429 assertions**.

| Fichier de test | Périmètre |
|---|---|
| `P9ChecklistCdcTest` | Checklist CDC §6 : recherche, ﬁltres ET/OR, colonnes togglables/triables, exports CSV, envoi email filtré (tags résolus) |
| `P5RbAcContratsTest` | RBAC et contrats d'accès — garde document + tests IDOR (403) |
| `PvModuleIntegrationTest` | Workﬂow PV complet : création → envoi → signature → PDF FR/AR |
| `ImportMoulinetTest` / `ImportPagesTest` | Imports CSV/XLSX/XML/JSON : lignes invalides jamais committées |
| `CdcDocumentTypesTest` | Documents de chaque type (pv, attestation, décision) |
| `ReunionsModuleTest` | Cycle réunion → convocation → CR → PV |
| `P7ArchivageAuditTest` | Archivage (disk privé) + journal d'audit |
| `P8WorkflowEngineTest` | Moteur de workﬂow de réunion |
| `SocleCriteriaTest` / `AdminPanelAccessTest` | Socle, rôles, IDOR |

Warnings : 2 (transliterator, bénin).

## 4. Conformité CDC §6 — Filament natif (0 réécriture)

| Exigence CDC §6 | Couverture | Preuve |
|---|---|---|
| Recherche multi-colonnes | `TextColumn::searchable()` | `P9ChecklistCdcTest` |
| Filtres ET/OR | `SelectFilter` + `TrashedFilter` | `P9ChecklistCdcTest` + `ImportMoulinetTest` |
| Colonnes personnalisables / tri | `->toggleable()`, `->sortable()` | test `toggleable columns` |
| Exports | `ExportAction` + `UserExporter` (5 colonnes) ; aucun `maatwebsite/excel` | `UserExporter` |
| Envoi emails ﬁltrée | `SendEmailBulkAction` (bulk) | `P9ChecklistCdcTest` + `AdminPanelAccessTest` |

## 5. Imports — validate-then-commit

- `BaseImport` : `parseFile(csv/xlsx/xml/json)` → `buildRows` → `validateRow` → `commit(DB::transaction)`.
- `EnseignantImport` : aliases 12 libellés, règles email unique + enum role + exists établissement.
- `TheseImport` : aliases 15 libellés, rules exists User + commission.nom.
- Écrans `ImportEnseignants` / `ImportTheses` accessibles en 1 clic depuis les listes.

## 6. Audits et sécurité

- `composer audit` : **« No security vulnerability advisories found. »**
- Politiques RBAC : `UserPolicy`, `DossierPolicy`, `ReunionPolicy`, `DecisionPolicy` — IDOR 100 % verts.
- `route:list` : aucune route publique involontaire (tout sous `auth` + `verified` + `role`).

## 7. UAT commission pilote

Scénario : inscription doctorant → création dossier → réunion commission → décision soutenance.
Seed « Informatique » (président + 2 directeurs + 1 doctorant).
**Résultat favorable**, 1 écart mineur (grade non triable, P3).

## 8. Preuves

- `storage/app/private/evidence/` : 7 PDF (pv-demo-fr/ar, p4-attestation-ar-rtl, p4-invitation, p4-decision, p6-attestation-reussite, p6-pv-reunion-signe).
- `storage/app/private/signatures/*.png` (démo).
- `packages/salsabil-ennaiem/pv-module/tests/artifacts/` (PDF arabes).

## 9. Liste d'écarts

| # | Écart | Priorité | Statut |
|---|---|---|---|
| 1 | Colonne `grade` non triable dans UsersTable | P3 — mineure | À faire |
| 2 | Export XLSX non proposé (CSV conforme CDC) | P4 — hors scope | Clos |
| 3 | ~~419 CSRF en test~~ — artefact config cache, corrigé | — | Clos |

## 10. Conclusion

Recette **favorable**. Suite verte (87/87, 429 assertions), audit propre, checklist §6 couverte, imports sûrs, UAT validé. **P9 clôturé.**