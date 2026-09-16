# Livrables CDC §5 — Manifeste d'inventaire (uma)

> **CDC §5** : « La propriété de l'ensemble des prestations — documents de conception, code
> source **PHP 8 (pas de cryptage)**, fichiers graphiques (PSD, Al) et fichiers d'installation
> et de paramétrage — sera intégralement transférée au profit de l'Université de la Manouba. »
>
> **État (2026-09-15)** : A1 **levé par défaut sûr** (identité en `07_licence_identite/IDENTITE_DEPOT.md`) ·
> P9 **clôturé favorablement** (87/87 tests verts, 429 assertions ; rapport + dossier
> déploiement en `06_installation_parametrage/`). Assemblage physique en cours → archive ZIP.

## 1. Contenu du dossier

| Sous-dossier | Contenu | Source |
|---|---|---|
| `01_sources_application/` | Code source PHP 8 de la plateforme (hors `vendor/`, `node_modules/`, `.git/`, `.env`, sqlite) | ce dépôt (`uma`) |
| `02_sources_package/` | Code source PHP 8 du package documentaire `pv-module` (tags `v1.0.0`→`v1.0.2`) | dépôt Packagist/GitHub `salsabil-ennaiem/pv-module` |
| `03_documents_conception/` | Cahier des charges, stratégie, registre de décisions, guides d'intégration | `Cdc.md`, `STRATEGIE_*`, `DECISIONS_UMA.md`, `NOUVELLE_APP_GUIDE_INTEGRATION.md`, ... |
| `04_graphiques/` | **Aucun fichier PSD/AI livré à ce jour** (attente logos/en-têtes officiels, A3) | — |
| `05_captures_evidences/` | PDF de démo FR/AR, attestation RTL, PV signés, signatures | `storage/app/private/evidence/`, `storage/app/private/signatures/` |
| `06_installation_parametrage/` | `.env.example`, configs publiées, **rapport de recette P9**, **dossier de déploiement CCK/RNU** | `.env.example`, `config/*.php`, `phpunit.xml` |
| `07_licence_identite/` | Licence MIT du package, **identité de livraison A1** (`IDENTITE_DEPOT.md`) | package `LICENSE` |
| `archive/` | Archive ZIP finale `PlateformeUMA_Livrables_Cdc5_YYYY-MM-DD.zip` | — |

## 2. Procédure d'assemblage

```powershell
# Depuis la racine du dépôt (uma/) :
robocopy . livrables_cdc5\01_sources_application /E /XD vendor node_modules .git storage\framework ^
    storage\logs storage\app\private /XF .env *.sqlite *.cache
# Package :
robocopy <dépôt pv-module> livrables_cdc5\02_sources_package /E /XD vendor .git
# Docs : copier les items du §3.
# Captures : copier storage/app/private/evidence + signatures.
# Config : .env.example + config/*.php + phpunit.xml.
# Identité : 07_licence_identite/ (LICENSE + IDENTITE_DEPOT.md).
# ZIP : Compress-Archive livrables_cdc5\** -> archive\PlateformeUMA_Livrables_Cdc5_YYYY-MM-DD.zip
```

> **Garde-fous** : jamais de `.env`, jamais de base SQLite de recette, jamais de certificats
> privés. Code livré **en clair (non crypté)** comme l'exige le CDC.