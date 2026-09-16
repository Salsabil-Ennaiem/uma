# Identité du livrable — Décision A1 (défaut sûr, non bloquante)

> **Statut** : le dépôt UMA n'ayant pas été joignable à la clôture P10, la décision A1 est
> appliquée **par défaut sûr** : pas de renommage du package, cession intègre par contrat.
> Ce document constitue l'identité officielle du livrable CDC §5.

---

## 1. Informations générales

| Champ | Valeur |
|---|---|
| Projet | Plateforme UMA — Gestion des réunions de soutenance |
| Commanditaire | Université de la Manouba (UMA) |
| Cahier des charges | `Cdc.md` (1071 lignes) |
| Date de livraison | 2026-09-15 |
| Statut | **Prêt à livrer** (recette P9 clôturée favorablement) |

## 2. Identité technique

| Champ | Valeur |
|---|---|
| Application | `uma` — Laravel 13, PHP ^8.3, Filament 5.0 |
| Namespace app | `App\` |
| Package | `salsabil-ennaiem/pv-module` (Packagist + GitHub), tag `v1.0.2` |
| Namespace package | `SalsabilEnnaiem\PvModule` (conservé — A1) |
| Licence | MIT (package `LICENSE` ; application idem) |
| Code | PHP 8, **non crypté** (CDC §5) |
| Fichiers graphiques | Aucun PSD/AI fourni (A3 en attente) |

**Renommage** : option en réserve (1 commit Rector dans `ASSUMPTIONS.md` ID-01).
Non appliqué en l'absence de décision formelle de l'UMA.

## 3. Cession de la propriété intellectuelle (CDC §5)

La section 5 du cahier des charges prévoit le transfert intégral de la propriété des
prestations (documents de conception, code PHP 8, fichiers graphiques, fichiers
d'installation/paramétrage) au profit de l'Université de la Manouba.

Cet acte sera formalisé à la signature, avec :
- transfert de propriété du code et documents ;
- licence MIT conservée pour le package (open source) ;
- fichiers graphiques à produire par l'UMA (non livrés).

## 4. Contenu de l'archive livrée

| Sous-dossier | Contenu |
|---|---|
| `01_sources_application/` | Code source app uma (hors vendor, node_modules, .git, .env, sqlite) |
| `02_sources_package/` | Code source pv-module (repo autonome) |
| `03_documents_conception/` | Cdc, stratégie, registre de décisions, guides |
| `04_graphiques/` | *En attente* |
| `05_captures_evidences/` | PDF démo, attestations, signatures |
| `06_installation_parametrage/` | `.env.example`, configs, rapport recette P9, dossier déploiement |
| `07_licence_identite/` | MIT, identité du dépôt, présent document |

> Garde-fous : jamais de `.env`, jamais de base SQLite de recette, jamais de certificats.
> Code livré en clair.

## 5. Traçabilité Git

- Application uma : commits P5–P9 (à pousser).
- Package pv-module : tag `v1.0.2`, lock `composer.lock` → zipball GitHub `dcd7af8`.

## 6. Engagements

Livraison conditionnée à : acte de cession signé · fichiers graphiques (si fournis) ·
décision ﬁnale sur renommage · validation déploiement CCK.