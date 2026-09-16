# STRATÉGIE & ROADMAP — Plateforme Doctorale UMA (Lot 2 du Cdc.md)

> Document consolidé et validé après revue critique. Il fixe : l'**objectif global**, le **but de chaque étape**, la **cible** de chaque étape (**Voyager** = monolithe / **Package** = `pv-module` / **Nouvelle app** = plateforme UMA), l'ordre et la priorité, les risques transversaux et le registre d'hypothèses.
>
> Les prompts d'exécution correspondants sont dans le dossier `prompts_uma/` (un prompt par étape, ciblé par projet).

---

## 1. Objectif global

Construire la **plateforme de gestion de la formation doctorale de l'UMA** (Lot 2 du `Cdc.md`) : une **nouvelle application modulaire Laravel 13 + Filament 5** qui **réutilise** le moteur documentaire du package **`salsabil-ennaiem/pv-module`** (déjà extrait de Voyager) et **réécrit en Filament** le code métier spécifique (réunions, workflows) **dans la nouvelle app**.

- **Ne PAS cloner le monolithe Voyager.**
- **Réutiliser** le package + les docs de conception.
- **Tout ce qui est générique et déjà écrit → package ; tout ce qui est spécifique UMA → nouvelle app.**

---

## 2. Contexte & état des lieux (points durs actés)

| Fait | Détail | Conséquence |
|---|---|---|
| Voyager est un monolithe | Laravel 11 + Filament 4, Campus 3D, logique ENIS/Campus | Pas de clonage → extraction |
| Extraction « moteur documentaire » **déjà faite** | `packages/salsabil-ennaiem/pv-module` (`SalsabilEnnaiem\PvModule\`) : modèles `Pv/PvSignature/PvTemplate/PvValidation`, services `PvService/PdfService/SignatureService`, contrats `CanManagePv/ApprovalRules/ParticipantResolver`, migrations, tests, i18n fr/ar/en | Cœur réutilisable prêt |
| Package branché en `@dev` (path repo) | `composer.json` de Voyager | **À taguer/publier** pour consommation propre |
| `Reunion/Invitation/Presence` **non extraits** (volontaire) | Domaine trop spécifique | **À réécrire en Filament** dans la nouvelle app |
| Guides de référence déjà rédigés | `NOUVELLE_APP_GUIDE_INTEGRATION.md`, `PV_MODULE_CDC_COVERAGE.md`, `WORKFLOW_ET_COMPTES.md`, `LOT2_FILAMENT_PLAN.md` | Les appliquer, ne pas réinventer |
| UMA injoignable à ce jour | | Stratégie « configuration over code » + registre d'hypothèses |

---

## 3. Principes directeurs (règles d'or — à respecter à chaque étape)

1. **Une seule version du package.** Jamais de fork, jamais de copie locale dans l'app (`Modules/Voyager` = interdit). Toute évolution documentaire se fait **dans le package**, consommé via Composer.
2. **Un seul panneau admin Filament** (`/admin`) = obligation CDC §5 (même console pour le CMS et la plateforme). L'espace usager `/portal` (CDC §2) et le front public sont **d'autres zones** ; l'UI n'est **pas dupliquée** — les **services et Policies sont partagés**, filtrage par `canAccessPanel()`.
3. **RBAC natif** : enum `UserRole` + Gates/Policies **fail-closed** (rôle absent → 403). **Pas de spatie/laravel-permission.**
4. **Règles métier paramétrables en base** (alignement JORT), jamais codées en dur.
5. **Les 3 risques transversaux** (PI, signature, RTL) → **défaut sûr + adaptateur (Strategy/Resolver) + registre d'hypothèses**. Pas de config spéculative hors de ces risques.
6. **Contrats du package = source de vérité.** L'app implémente les contrats (`CanManagePv`, `ApprovalRules`, `ParticipantResolver`) ; elle ne les **re-déclare pas**.
7. **Gates de tests à chaque étape** : feature tests sur Policies/Resources, anti-IDOR.
8. **Hébergement CCK / normes RNU** (CDC) : pensé dès la conception (staging, SSL, sauvegardes, CI), pas en fin de projet.

---

## 4. Les 3 risques transversaux & registre d'hypothèses

> Hypothèses par défaut car l'UMA est injoignable. **À valider dès que possible.** Le code doit rester neutre : chaque point n'a qu'un **mécanisme de bascule unique**.

| ID | Risque | Hypothèse par défaut (défaut sûr) | Mécanisme de bascule | À demander à l'UMA |
|---|---|---|---|---|
| **R1** | Propriété intellectuelle (CDC §5 : cession intégrale) | **Conserver le namespace `SalsabilEnnaiem`** + cession **par contrat juridique** (cas standard) | Renommage ponctuel par script Rector le jour J (option en réserve, **1 commit**, pas de fork) | Nom/licence du package final, dépôt de livraison |
| **R2** | Validité juridique des signatures | **Signature simple** (image + hash + horodatage) + **trace de conformité** (mécanisme + date enregistrés dans le document) | Resolver `SIGNATURE_DRIVER` : `simple_image` → `qualified` (implémentation `SignatureStrategy` dans l'app, branchée sur les contrats du package) | Niveau de conformité attendu (arrêtés, PV de soutenance) |
| **R3** | RTL arabe (attestations) | Standard `dir="rtl"` + mise en page **confinée aux templates Blade** (aucune logique métier RTL) | Validation visuelle dès le seed des templates (Étape P4) avec textes arabes longs | Conformité des modèles officiels UMA |

**Registre (à reporter dans le README du package et dans l'app)** — format : `ID · hypothèse · mécanisme de bascule · décideur · [date de validation]`.

---

## 5. Roadmap détaillée (ordre & priorité)

> **Cible :** 🟦 Nouvelle app (plateforme UMA) · 🟧 Voyager (monolithe) · 🟪 Package `pv-module` · ⬜ Transversal (docs/décisions).
> L'ordre = ordre d'exécution des prompts. **P1 et P2 sont parallèles** (aucune dépendance). P4/P5 peuvent partiellement s'entrelacer mais l'ordre listé est la priorité.

| # | Prompt | Étape | Objectif (le but) | Cible | Dépend de | Livrables | Critères d'acceptation |
|---|---|---|---|---|---|---|---|
| **P1** | `P1_socle_nouvelle_app.md` | Étape 0 — Socle | Créer l'application Laravel 13 + Filament 5, 1 panneau `/admin`, auth/rôles natifs, structure des domaines, config plateforme (flags limités aux 3 risques) | 🟦 Nouvelle app | — (rien : indépendant du package) | App qui tourne + `UserRole` (enum) + `canAccessPanel()` fail-closed + tests de base | `php artisan serve` OK ; un rôle non déclaré → 403 ; tests socle verts |
| **P2** | `P2_package_v1.0.0_voyager.md` | Étape 0.5 — Package prêt à livrer | Rendre le package **consommable et livrable** : trace de conformité signature (R2), validation RTL-AR des templates (R3), i18n propre, tests verts, README + registre d'hypothèses, **tag `v1.0.0` de validation sur le repo package autonome** (`subtree split` — jamais un tag du monolithe) | 🟧 Voyager + 🟪 Package | — (parallèle à P1) | Repo package autonome + tag `v1.0.0` (validation) ; tests passer ; README + ASSUMPTIONS ; trace conformité en base | `composer install` dans une app jetable OK ; PV signé → PDF FR/AR ; trace + horodatage présents ; tag visible sur le repo package (le tag de livraison final sera posé en P10 selon R1) |
| **P3** | `P3_integration_package.md` | Étape 1 — Intégration | Consommer `pv-module` via Composer (VCS ou path), publier config/lang/migrations, seeder templates, `storage:link`, mapping `user_model` | 🟦 Nouvelle app | P1 + P2 | Moteur documentaire vivant dans la nouvelle app | PV créé/signé dans l'app → PDF FR/AR ; notifications mail+base |
| **P4** | `P4_types_documents_templates.md` | Étape 2 — Document engine du CDC | Déclarer les types CDC (`attestation`, `arrete`, `decision`, `invitation`, `diplome`, `fiche_acces`) via `config('pv-module.types')`, seed templates FR/AR, valider RTL (R3) | 🟦 Nouvelle app (+ 🟪 si extension package nécessaire : batch, logos, en-têtes) | P3 | Templates seedés ; attestation AR + invitation générées | Attestation AR rendue correcte (RTL) + invitation officielle PDF |
| **P5** | `P5_rbac_contrats_package.md` | Étape 3 — RBAC métier | Implémenter les 3 contrats du package sur les rôles UMA ; resolver de signature (driver config, R2) ; modéliser la hiérarchie métier `Commission` **dans la nouvelle app uniquement** (le package est volontairement agnostique — aucun modèle `Organisme`) | 🟦 Nouvelle app | P3 | Classes `CanManagePv`, `ApprovalRules`, `ParticipantResolver` + Policies | Aucune action possible sans garde du package ; tests Policies + anti-IDOR |
| **P6** | `P6_module_reunions_filament.md` | Étape 4 — Réunions (le cœur du travail) | Réécrire en Filament : Réunion (statuts, ODJ, membres auto), Invitations (mail + PDF), Présence, Décisions, export décisions → PV, corbeille, journal de logs ; PV via package | 🟦 Nouvelle app | P5 | Module Réunions fonctionnel | **Slice verticale validée (DoD)** : Dépôt → ODJ → PV signé → Attestation AR générée et conforme ; parcours réunion → présence → PV signé du bout en bout |
| **P7** | `P7_archivage_audit.md` | Étape 5 — Archivage + audit | `Document`/`DocumentVersion` polymorphiques (mallette), `audit_logs` append-only, module « rapports et états » (HTML/PDF, marges, orientation) | 🟦 Nouvelle app | P3 (+ P6 partiel) | Dossier numérique + traçabilité | Toute action sur réunion loggée ; décisions visibles par année d'inscription |
| **P8** | `P8_workflow_engine_base.md` | Étape 6 — Moteur de workflow | `WorkflowDefinition/Instance/Transition` **paramétrables en base**, interface posée tôt ; recouvrement des workflows A/B/C (inscriptions 1→5, soutenance, demandes diverses + ticketing) | 🟦 Nouvelle app | P5 (+ P6 partiel) | Workflows A/B/C opérationnels | Une inscription 1ʳᵉ année et une soutenance pilotées par le moteur |
| **P9** | `P9_recette_deploiement.md` | Étape 7 — Recette + déploiement | UAT commission pilote, imports CSV/XLSX + moulinet thèses, `composer audit`, tests anti-IDOR, déploiement prévu **CCK / normes RNU** | 🟦 Nouvelle app | P4→P8 | Recette validée + cible de déploiement documentée | Checklist CDC §6 vérifiée ; aucun IDOR ; audit 0 critique |
| **P10** | `P10_cloture_assumptions_uma.md` | Clôture — Décisions UMA & conformité | Revoir le registre d'hypothèses, re-solliciter l'UMA (R1/R2/R3 + workflow), finaliser les livrables CDC §5 (sources, docs de conception, licence) | ⬜ Transversal (+ 🧡 Package) | P9 (ou parallèle avant recette) | Décisions actées + livrables CDC | Chaque ligne du registre a une décision ou une date de re-sollicitation |

---

## 6. Ordre de priorité résumé

```
P1 (socle) ────────┐
P2 (package v1.0) ─┤
                   ├──► P3 (intégration) ─► P4 (types doc) ─► P5 (RBAC) ─► P6 (réunions) ─► P7 (archivage/audit) ─► P8 (workflows) ─► P9 (recette) ─► P10 (clôture UMA)
```

- **P1 et P2 en parallèle** (aucune dépendance mutuelle).
- **P5 ne doit jamais être sauté avant P6** : aucune action Filament sans garde du package.
- **P10 peut être préparé en parallèle** (préparer les questions UMA) mais la décision finale bloque la livraison.

---

## 7. Points d'attention permanents

- **Garde-fous anti-régression** : le monolithe `voyager/app/` contient encore des doubles legacy (`PV.php`, `PdfService.php`, `SignatureService.php`) — utiles pour la démo Voyager, **ignorés** par la nouvelle app (une seule source de vérité = le package).
- **`workflow`** : poser l'interface du moteur tôt (au plus tard P5), l'implémentation base ne vient qu'en P8 — sinon la migration des transitions codées en dur coûtera cher.
- **Slice verticale = Definition of Done de P6** : « Dépôt → ODJ → PV signé → Attestation AR conforme » est **bloquant** pour clôturer P6 (et non une simple note douce) ; ne pas lancer P7/P8 tant qu'elle n'est pas validée.

## 8. Questions ouvertes (à confirmer par l'utilisateur)

- Nom et chemin exact de la nouvelle app (par défaut : `stageNashd/uma`, frère de `voyager`).
- Dépôt Git du package : **repo Git autonome** (extrait via `subtree split`, tag de validation `v1.0.0`) + GitHub privé ou repo local ; le tag/licence de livraison final (R1) est tranché en P10.
- Langue des prompts/code : français par défaut, code/comments en anglais si souhaité.

## 9. Documents de référence

- `Cdc.md` (Lot 2) — exigences fonctionnelles
- `NOUVELLE_APP_GUIDE_INTEGRATION.md` — plan d'intégration (Étapes 0→8)
- `PV_MODULE_CDC_COVERAGE.md` — couverture CDC du package
- `WORKFLOW_ET_COMPTES.md` — diagrammes d'états + matrice acteurs/actions
- `LOT2_FILAMENT_PLAN.md` — plan Filament du back-office
- `packages/salsabil-ennaiem/pv-module/README.md` — doc du package
- `prompts_uma/P*.md` — prompts d'exécution par étape

> **Synchronisation** : ce MD et les prompts sont un seul document en deux fichiers. Toute modification de l'un doit être répercutée dans l'autre — consigner le duo « MD + prompt » comme atomicité de modification.