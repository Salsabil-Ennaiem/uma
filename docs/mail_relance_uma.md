# Mail relance UMA — État du périmètre à ce jour & questions (Annexe : matrice CDC)

> Émetteur : éditeur · Destinataire : UMA (direction / référent).
> Cadrage : document de **négociation**, jamais un aveu de manque. Conforme au Cdc.md §2 :
> *« la spécification détaillée sera faite durant la phase d'analyse »* — cette phrase sert de
> bouclier pour toute ligne « à spécifier ».

## Objet
Plateforme UMA — État du périmètre à ce jour et questions avant recette

## Corps

Bonjour,

Dans le cadre de la préparation de la recette de la plateforme UMA, nous vous remettons l'état
du **périmètre couvert à ce jour** et nos **questions ouvertes** (annexe ci-jointe).

La convention que nous suivons est celle du cahier des charges (§2) : la spécification détaillée
se fait durant la phase d'analyse. Les lignes marquées « à spécifier ensemble » relèvent donc de
cette phase de travail conjointe et non d'un manque fonctionnel.

Nous vous remercions de :
1. valider le périmètre couvert et les priorités proposées pour les lignes « à spécifier » ;
2. répondre aux questions d'identification posées en bas de l'annexe (timing de la phase
   d'analyse, interlocuteurs désignés, jeux de données de recette).

Cordialement,
[Éditeur] — Plateforme UMA

---

## Annexe — Matrice du périmètre (brouillon)

Légende : **Couvert** = livré et testé · **Partiel** = socle livré, détails à spécifier ensemble
en phase d'analyse (CDC §2) · **À spécifier** = défini dans le CDC, à spécifier ensemble en phase
d'analyse (CDC §2).

| # | Module CDC | État | Points couverts à ce jour | Points à spécifier ensemble (phase d'analyse) |
|---|---|---|---|---|
| 1 | Structures institutionnelles | Couvert | Universités, écoles doctorales, établissements, commissions (gestion + hiérarchie) | Import automatique depuis inscription.tn, codes officiels |
| 2 | Annuaire doctorants & enseignants | Couvert | Utilisateurs, rôles, import CSV, liste filtrée, email groupé | Synchronisation annuaire universitaire, champs bilingues |
| 3 | Inscriptions 1ʳᵉ→5ᵉ année | Partiel | Dossier + moteur de workflow paramétrable (états, transitions, rôles) | Parcours 2ᵉ→5ᵉ différenciés (ODJ commission, PV, dérogation président université), seuils JORT |
| 4 | Paiements / reçus / attestations | Partiel | Reçu déposé, validation, attestation FR/AR générée | Intégration directe inscription.tn, horodatage officiel |
| 5 | Réunions & commissions | Couvert | Planification, ODJ, invitations, présences, quorum | Règles de quorum/unanimité paramétrées par type |
| 6 | PV & signatures | Partiel | Génération PV, signature simple + trace, rendu RTL | Modèles officiels UMA (logos, polices, en-têtes) |
| 7 | Décisions & arrêtés | Couvert | Décisions de commission, modèles, archivage | Circuit d'arrêté rectorat |
| 8 | Soutenance & diplômes | Partiel | Workflow : éligibilité ≥3 ans, rapporteurs, jury, planification anti-chevauchement, diplôme | Gestion de session détaillée, convocations officielles, lien MESRS |
| 9 | Réclamations / ticketing | Couvert | États, priorités, discussions, clôture, notifications | Règles de priorité métier |
| 10 | Réservations & planification | Couvert | Salles, anti-chevauchement jury/salle | Règles d'allocation inter-commissions |
| 11 | Archivage | Couvert | Documents versionnés, types CDC, audit | Rétention et purge |
| 12 | Rapports / états (MESRS) | Partiel | Génération PDF, états paramétrables | Gabarits officiels précis |
| 13 | Espaces usagers (doctorant / enseignant / administration) | À spécifier | Socle d'authentification et de routage existant ; espace doctorant minimal en cours | Écrans, accès, parcours utilisateurs |
| 14 | Communication / notifications | Couvert | Emails ciblés, notifications de workflow, relances | Campagnes multi-canaux |
| 15 | Multilingue FR/AR (RTL) | Partiel | Attestations FR/AR, rendu RTL des PV | Traduction intégrale de l'interface, libellés bilingues (lot planifié) |

## Questions ouvertes à l'UMA
- Q1 : priorités souhaitées pour les lignes « Partiel / À spécifier » (timing de la phase d'analyse) ?
- Q2 : interlocuteurs désignés et canal de travail pour cette phase ?
- Q3 : jeux de données disponibles pour la recette (annuaire, thèses, structures) — formats CSV/XLSX ?