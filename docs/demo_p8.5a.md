# Démo P8.5a — Workflow de ré-inscription (bout-en-bout)

> Scénario de répétition : 2ᵉ année, chemin nominal + branche dérogation (niveau ≥ 4).
> Prérequis : `php artisan migrate --seed` (seeders Workflow + Comptes recette).

## Chemin nominal (niveau 2)

1. Connexion `admin@uma.tn` / `password` → créer un Dossier (doctorant, année « 2ème »),
   puis action « **Démarrer le workflow** » → niveau 2 → état `demande` (workflow `reinscription`).
2. `agent@uma.tn` : « **Valider la demande** » → `valide_agent`.
3. `gestionnaire@uma.tn` : joindre le **rapport d'avancement** puis « **Inscrire en
   commission** » (garde: rapport requis) → `commission_examen`.
4. `president@uma.tn` : joindre le **PV** puis « **Valider la décision
   (PV)** » (garde: PV requis) → `pv_valide`.
5. Niveau 2 < 4 → `gestionnaire@uma.tn` : « **Poursuivre vers le paiement** » → `paiement_attendu`.
6. `doctorant@uma.tn` : « **Déposer le reçu** » → `recu_uploaded`.
7. `agent@uma.tn` : « **Valider le reçu** » en cochant « Paiement vérifié sur
   inscription.tn » → `recu_valide`.
8. « **Générer l'attestation** » → `attestation_generee` (notif doctorant : « attestation prête
   dans 72 heures »).
9. `admin@uma.tn` : « **Archiver** » → `archivee`. Terminus (définition active).

## Branche dérogation (niveau ≥ 4)

- Au lieu de l'étape 5 : « **Demander la dérogation** » → `derogation_requise`, puis
  `admin@uma.tn` : « **Approuver la dérogation** » (notif doctorant) → `derogation_accorde`,
  puis « **Poursuivre le paiement** » → `paiement_attendu` (rejoindre l'étape 6).

## Contrôles transverses

- Actions dynamiques en en-tête (état courant) ; échec de garde → notification « danger ».
- Bouton « **Historique du workflow** » : trace horodatée (audit trails).
- Notifications doctorant visibles en badge de notification.
- 7 comptes de recette (domaine `@uma.tn`, mot de passe commun `password`) :
  `admin`, `gestionnaire`, `agent`, `president`, `membre`, `directeur`, `doctorant`
  (créés par `UsersSeeder`, idempotent).