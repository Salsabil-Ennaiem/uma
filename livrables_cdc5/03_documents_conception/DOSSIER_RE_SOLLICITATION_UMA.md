# DOSSIER DE RE-SOLLICITATION UMA — Décisions de clôture (P10)

> **Objectif** : obtenir en **une réunion de 30 min maximum** les arbitrages qui font basculer
> les hypothèses du projet en **décisions actées**. Chaque question est factuelle : une décision
> possible + son impact. Préparé le **2026-09-15** — non distribué tant que l'UMA n'est pas
> joignable. Peut être présenté **sans le projet** (aucune référence au code nécessaire).
> Registre détaillé : `DECISIONS_UMA.md`.

## 1. Cadre (2 min)

- Livrables CDC §5 : le code source, les documents de conception et les éléments graphiques du
  portail sont **intégralement cédés à l'Université de la Manouba**.
- Les choix ci-dessous n'affectent **pas** la cession (déjà actée) : ils déterminent l'**identité**
  du dépôt livré, le **niveau de conformité** des documents signés et l'**alignement réglementaire**
  des workflows.
- À défaut de décision expresse, chaque point reste appliqué selon son « défaut sûr » (option en gras).

## 2. Décisions demandées (25 min)

### D1 — Identité du livrable logiciel (propriété intellectuelle, CDC §5)

**Question** : sous quel nom/licence le code livré est-il remis à l'Université de la Manouba ?

| Option | Effet |
|---|---|
| **Conserver le namespace `SalsabilEnnaiem` + cession par contrat (défaut sûr)** | Aucune transformation ; la cession des droits se fait par le contrat de prestation (cas standard du CDC). |
| Renommer selon une identité UMA | Un commit de renommage mécanique (namespace, autoload, publications) + vérification des tests — à poser avant livraison. |

**Impact si non tranché** : l'archive de livraison (CDC §5) ne peut recevoir sa licence/dépôt final ;
c'est la seule décision **bloquante** pour la mise en livraison.

### D2 — Conformité des signatures électroniques

**Question** : quel niveau de conformité pour les **PV de commission**, les **arrêtés** et les
**PV de soutenance** ?

| Option | Effet |
|---|---|
| **Signature simple avec trace de conformité (défaut sûr)** : image apposée + mécanisme + horodatage enregistrés dans le document | Conforme pour la gestion interne ; gratuite ; fonctionnelle dès le déploiement. |
| Signature **qualifiée** (eIDAS/PKI, certificat) | Nécessite un certificat PKCS#12 fourni par l'établissement/CCK et l'activation du driver dédié ; coût et délais d'approvisionnement éventuels. |

**Impact si non tranché** : application du défaut sûr (simple). Une bascule ultérieure est possible
sans régression des documents déjà produits (le mécanisme et l'horodatage sont déjà enregistrés).

### D3 — Modèles officiels des attestations (rendu arabe / RTL)

**Question** : les modèles d'attestations livrés (français + arabe, rendu droite-à-gauche) sont-ils
conformes aux **modèles officiels** de l'établissement (en-têtes, logos, polices arabes) ?

| Option | Effet |
|---|---|
| **Valider les modèles livrés (défaut sûr)** | Aucun travail supplémentaire ; production immédiate. |
| Fournir les **modèles officiels** (PDF/Word + logos) | Mise à jour des gabarits (confinée aux templates), nouvelle campagne de captures de validation. |

**Impact si non tranché** : les attestations livrées sont utilisables ; l'intégration de modèles
officiels reste planifiée en appui.

### D4 — Règles des parcours (workflows) par texte de loi

**Question** : validez-vous les définitions de parcours paramétrées (inscription 1ʳᵉ→5ᵉ année,
soutenance, réclamations) et leurs **seuils/délais** (validation 30 crédits en 4ᵉ année avec
dérogation du président ; arrêté du rectorat en 5ᵉ année ; quorum et modalités de vote des réunions) ?

| Option | Effet |
|---|---|
| **Valider les valeurs actuelles (défaut sûr)** | Rien à changer. |
| Fournir les valeurs/textes officiels (JORT/arrêtés)  | Ajustement **en base uniquement** (définitions de workflow, transitions, seuils) — jamais de code en dur. |

**Impact si non tranché** : parcours fonctionnel avec les valeurs actuelles ; alignement réglementaire
reporté jusqu'à réception des textes.

### D5 (option, si le temps le permet) — Recette avec données réelles

**Question** : une **commission pilote** et un **jeu d'importation** (annuaire des thèses /
enseignants) peuvent-ils être désignés pour la recette ?

| Option | Effet |
|---|---|
| Jeu réaliste fourni par le prestataire | Recette immédiate en environnement de test. |
| Extraction réelle (annuaire thèses encours, enseignants) | Formats et volumes à préciser (CSV/XLSX) ; implémentation de l'import configurée en conséquence. |

## 3. Déroulé (3 min de cadrage final)

| Temps | Sujet | Décideur côté UMA |
|---|---|---|
| 0–2 min | Cadre (cession CDC §5 + défaut sûr) | Direction / chef de projet |
| 2–9 min | D1 PI + D2 signature | Direction + services juridiques |
| 9–16 min | D3 modèles RTL + D4 workflows | Services concernés (doctorat) |
| 16–28 min | Réponses contradictoires / D5 | Les mêmes |
| 28–30 min | Clôture : chaque D → `validée` / `amendée` / `à corriger` + responsable | Tous |

> Chaque décision est transposée dans `DECISIONS_UMA.md` (registre) puis dans les notes de
> conformité (README app + package) avec date et responsable.