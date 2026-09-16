# Workflow de l'Application — Voyager (ERP Réunion ENIS)

## 1. Workflow Général

### 1.1 Gestion des Réunions

```
Création (brouillon) → Planification (planifiée) → En cours → Terminée
                                                      ↓
                                                 Annulée
```

| Statut | Description |
|--------|-------------|
| `brouillon` | Réunion créée, en cours d'édition |
| `planifiee` | Réunion programmée, invitations envoyées |
| `en_cours` | Réunion démarrée |
| `terminee` | Réunion clôturée |
| `annulee` | Réunion annulée (depuis n'importe quel statut) |

### 1.2 Workflow des Invitations

```
Admin/Chef crée une réunion
         ↓
Assigne un organisme
         ↓
Invite des membres (internes ou externes)
         ↓
Membre reçoit l'invitation (notification + email)
         ↓
Membre répond → Accepte / Refuse / Excuse (avec justificatif possible)
         ↓
Président/Chef valide ou rejette les excuses
         ↓
Lors de la réunion → Présent / Absent / Excusé
```

### 1.3 Workflow des PV (Procès-Verbal)

```
Réunion terminée
         ↓
[CONTRAINTE] La date_fin de la réunion doit être dans le passé
[CONTRAINTE] L'utilisateur doit être admin, responsable de l'organisme, ou président
         ↓
Utilisateur autorisé → "Générer le PV" (bouton dans le calendrier)
         ↓
[LOGIC] Si un PV existe déjà en brouillon → ouvre l'édition directement
[LOGIC] Si un PV existe déjà (autre statut) → ouvre le preview
[LOGIC] Si aucun PV → ouvre la page de création
         ↓
┌─ Page de création du PV ──────────────────────────────────┐
│  [CONTRAINTE] L'utilisateur doit AVOIR UNE SIGNATURE      │
│               téléchargée (avertissement si pas de sig.)  │
│                                                            │
│  1. Participants glissés par Drag & Drop dans les zones :  │
│     - N'importe quelle section du template                 │
│     - Zone "Signatures" en bas                             │
│     - Le créateur est auto-inclus (ne peut être retiré)    │
│                                                            │
│  2. Choix du template (mode édition) :                     │
│     - "Conserver le template actuel" (current)             │
│     - "Utiliser le nouveau template" (new)                 │
│                                                            │
│  3. Date limite de signature (optionnelle)                 │
│                                                            │
│  4. Action : "Générer & Envoyer pour signature"            │
│     OU "Sauvegarder les modifications" (mode édition)      │
└────────────────────────────────────────────────────────────┘
         ↓
[CONTRAINTE] Au moins 1 participant requis (créateur auto-inclus)
[CONTRAINTE] Le créateur doit avoir une signature uploadée
[CONTRAINTE] Signature_deadline validée → `after:now`
         ↓
PV créé en statut "brouillon"
         ↓
Envoi pour signature → statut "en_attente"
         ↓
[LOGIC] Le créateur est auto-signé (si signature uploadée)
[LOGIC] Participants reçoivent notification + email
[LOGIC] Les validateurs sont stockés avec placement structuré :
        `[{userId, sectionId}]` (sectionId pour savoir où
        placer la signature dans le template)
         ↓
Chaque participant valide, rejette ou signe le PV
         ↓
        ├── Validation simple
        │   [CONTRAINTE] Si signature_deadline dépassée → refusé
        │
        ├── Signature (importe la signature uploadée)
        │   [CONTRAINTE] Si signature_deadline dépassée → refusé
        │   [CONTRAINTE] L'utilisateur doit avoir une signature
        │
        └── Rejet
            [CONTRAINTE] commentaire obligatoire (max 1000 car.)
            [LOGIC] PV repasse en "brouillon" pour édition
         ↓
[CONTRAINTE] PV devient "valide" si :
             - Le responsable de l'organisme a validé, OU
             - Le créateur du PV a validé, OU
             - Tous les validateurs ont validé
         ↓
Téléchargement du PDF final (signatures intégrées)
         ↓
┌─ Modification après envoi ────────────────────────────────┐
│  Si un PV déjà envoyé est modifié (nouveaux validateurs)  │
│  → Un NOUVEAU PV est créé par duplication (replicate)     │
│  → L'ancien PV est préservé comme version dans le nouveau │
│  → Les validations en cours ne sont pas affectées          │
└────────────────────────────────────────────────────────────┘
```

#### Gestion des PVs en Double (cleanupDuplicatePVs)

Quand plusieurs PVs existent pour une même réunion (cas rare) :

| Cas | Comportement |
|-----|-------------|
| Contenu identique | Supprime le doublon, garde le plus récent |
| Seul le titre ou la deadline diffère | Fusionne dans le PV le plus récent |
| Contenu différent | Préserve l'ancien PV comme **version** dans le PV le plus récent |

### 1.4 Cycle Complet (Parcours Type)

1. **Admin/Chef** se connecte → crée un organisme (ou utilise un existant)
2. **Admin/Chef** ajoute des membres à l'organisme
3. **Admin/Chef** crée une réunion (titre, date, type, lieu)
4. **Admin/Chef** invite des membres
5. **Membres** recoivent l'invitation → répondent
6. **Admin/Chef** suit les réponses sur le tableau de bord
7. **Réunion** se déroule → présence marquée
8. **Admin/Chef** génère le PV automatiquement
9. **Participants** valident ou rejettent le PV
10. **PV** signé numériquement → PDF final téléchargeable

---

## 2. Comptes de Test

Tous les mots de passe sont : **`password`**

| Rôle | Nom | Email | Mot de passe |
|------|-----|-------|-------------|
| **Admin** | Admin User | `admin@s.tn` | `password` |
| **Chef + Membre** | Khaled ELLEUCH | `khaled@s.tn` | `password` |
| **Membre uniquement** | Personnel ENIS | `personnel@s.tn` | `password` |

### Rôles et Permissions

#### Organismes

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Voir tous les organismes | ✅ | ❌ | ❌ |
| Voir son organisme | ✅ | ✅ | ✅ |
| Créer un organisme | ✅ | ❌ | ❌ |
| Modifier un organisme | ✅ | ✅ (son org.) | ❌ |
| Supprimer un organisme | ✅ | ❌ | ❌ |
| Publier/Dépublier un organisme | ✅ | ❌ | ❌ |
| Changer le responsable | ✅ | ❌ | ❌ |
| Gérer les types d'organismes | ✅ | ❌ | ❌ |

#### Membres d'organisme

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Ajouter un membre | ✅ | ✅ (son org.) | ❌ |
| Modifier le poste d'un membre | ✅ | ✅ (son org.) | ❌ |
| Retirer un membre | ✅ | ✅ (son org.) | ❌ |
| Retirer un membre avec poste protégé | ✅ | ❌ | ❌ |
| Attribuer un poste protégé (chef, président, responsable, gérant) | ✅ | ❌ | ❌ |

#### Réunions

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Voir toutes les réunions | ✅ | ❌ | ❌ |
| Voir les réunions de son organisme | ✅ | ✅ | ❌ |
| Voir les réunions où invité | ✅ | ✅ | ✅ |
| Créer une réunion | ✅ | ✅ (son org.) | ❌ |
| Modifier une réunion | ✅ | ✅ (son org.) | ❌ |
| Supprimer une réunion | ✅ | ✅ (son org.) | ❌ |
| Changer le statut (brouillon → planifiée → en_cours → terminée) | ✅ | ✅ (son org.) | ❌ |
| Annuler une réunion | ✅ | ✅ (son org.) | ❌ |
| Exporter les réunions (PDF/Excel) | ✅ | ✅ | ✅ |

#### Invitations & Présence

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Inviter des participants | ✅ | ✅ (ses réunions) | ❌ |
| Gérer les invitations | ✅ | ✅ (ses réunions) | ❌ |
| Répondre à une invitation (accepter/refuser/excuser) | ✅ | ✅ | ✅ |
| Joindre un justificatif d'excuse | ✅ | ✅ | ✅ |
| Valider/Rejeter les excuses | ✅ | ✅ (ses réunions) | ❌ |
| Marquer la présence (présent/absent/excusé) | ✅ | ✅ (ses réunions) | ❌ |

#### Procès-Verbaux (PV)

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Générer un PV (réunion terminée dans le passé) | ✅ | ✅ (ses réunions) | ❌ |
| Modifier un PV en brouillon (titre, contenu, template, deadline, placements) | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Changer de template (current → new) en mode édition | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Glisser-déposer participants dans des sections spécifiques | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Supprimer un PV en brouillon | ✅* | ✅ (créateur/responsable)* | ❌ |
| Dupliquer un PV (automatique si validateurs changent après envoi) | ✅ | ✅ | ❌ |

> * La **policy** l'autorise, mais le **controller n'implémente pas** de méthode `destroy()` et il n'y a **pas d'interface UI**. Non fonctionnel actuellement.
| Envoyer un PV pour validation | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Créer + Envoyer directement (storeAndSend) | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Valider un PV (si validation en attente + deadline non dépassée) | ✅ | ✅ | ✅ |
| Rejeter un PV (si validation en attente) | ✅ | ✅ | ✅ |
| Signer un PV (si validation en attente + signature uploadée + deadline non dépassée) | ✅ | ✅ | ✅ |
| Télécharger le PDF final | ✅ | ✅ (créateur/responsable/président) | ✅ (si participant avec validation) |
| Ajouter/Retirer des validateurs | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Définir une date limite de signature | ✅ | ✅ (créateur/responsable/président) | ❌ |
| Voir l'historique des versions du PV | ✅ | ✅ (créateur/responsable/président) | ✅ (si participant avec validation) |

#### Signature Numérique

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Uploader sa signature (JPEG/PNG/GIF, max 2 Mo) | ✅ | ✅ | ✅ |
| Dessiner sa signature (canvas) | ✅ | ✅ | ✅ |
| Supprimer sa signature | ✅ | ✅ | ✅ |

#### Templates PDF

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Voir les templates par défaut | ✅ | ✅ | ✅ |
| Créer ses propres templates (export + PV) | ✅ | ✅ | ✅ |
| Modifier ses propres templates | ✅ | ✅ | ✅ |
| Supprimer ses propres templates | ✅ | ✅ | ✅ |
| Réinitialiser ses templates | ✅ | ✅ | ✅ |
| Modifier/Supprimer les templates d'autres utilisateurs | ✅ | ❌ | ❌ |

#### Dashboard & Statistiques

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Dashboard général (KPI, heatmap, alertes système) | ✅ | ❌ | ❌ |
| Dashboard de son organisme (taux présence, tendances) | ❌ | ✅ | ❌ |
| Dashboard personnel (mes réunions, invitations en attente) | ✅ | ✅ | ✅ |
| Voir les notifications | ✅ | ✅ | ✅ |
| Marquer une notification comme lue | ✅ | ✅ | ✅ |

#### Paramètres

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Accéder à la page paramètres | ✅ | ✅ | ✅ |
| Modifier la langue (FR/EN/AR) | ✅ | ✅ | ✅ |
| Configurer l'export auto vers calendrier (Google/Outlook) | ✅ | ✅ | ✅ |
| Ajouter des destinataires email supplémentaires | ✅ | ✅ | ✅ |
| Gérer les utilisateurs (CRUD) | ✅ | ❌* | ❌* |

> * Aucun `UserPolicy` n'existe. L'admin a accès via `Gate::before`. Les non-admin n'ont pas d'accès explicite (Filament refuse par défaut).
| Gérer les rôles | ✅ | ❌ | ❌ |

#### Campus 3D

| Action | Admin | Chef | Membre |
|--------|-------|------|--------|
| Accéder au plan interactif 3D du campus | ✅ | ✅ | ✅ |

#### Contraintes et Règles de Validation

| Règle | Détail |
|-------|--------|
| Création d'une réunion : date_debut ne peut pas être dans le passé | `date_debut > now()` |
| Création d'une réunion : date_fin doit être le même jour que date_debut | Même jour calendaire |
| Création d'une réunion : l'organisme doit être publié | `organisme.is_published = true` |
| Création d'une réunion : le président ne peut pas être ajouté comme participant | Président exclu des participants |
| Création d'une réunion : le président doit être membre de l'organisme | Vérifié avant création |
| Génération d'un PV : la réunion doit être terminée | `statut = terminee` |
| Génération d'un PV : la date_fin doit être dans le passé | `date_fin < now()` |
| Génération d'un PV : si un PV brouillon existe → mode édition ; si autre statut → preview | Logique côté frontend (reunion-detail.js) |
| Nettoyage des PVs en double : contenu identique → supprimer ; titre/deadline différent → fusionner ; contenu différent → archiver comme version | `cleanupDuplicatePVs()` |
| Création d'un PV : le créateur doit avoir une signature uploadée | `storeAndSend()` ligne 305 |
| Création d'un PV : `contenu` est obligatoire (`required\|array`) | `UpdatePVRequest` |
| Modification d'un PV : choix du template (`current` ou `new`) | `template_choice` dans `UpdatePVRequest` |
| Modification d'un PV : placement des validateurs par section (`template_choice`) | `receiver_placements.*.sectionId` |
| Modification d'un PV déjà envoyé (avec validations) : duplication automatique en nouveau PV | `save()` lignes 457-498 |
| Validation d'un PV : statut doit être "brouillon" pour modification/envoi | `statut = brouillon` |
| Validation d'un PV : commentaire obligatoire en cas de rejet | Max 1000 caractères |
| Validation d'un PV : si `signature_deadline` dépassée → rejet | `validatePV()` ligne 657 |
| Signature : l'utilisateur doit avoir une signature uploadée | Vérifié avant signature |
| Signature : si `signature_deadline` dépassée → refusé | `signPV()` ligne 733 |
| Signature : le créateur est auto-signé si signature uploadée | `sendToParticipantsInternal()` ligne 939-948 |
| Date limite : validée `after:now` à la création | `storeAndSend()` ligne 300 |
| Date limite : vérifiée `isPast()` à la validation et signature | `validatePV()` et `signPV()` |
| Gestion des membres : les postes protégés (chef, président, responsable, gérant) non modifiables par Chef | Réservé à l'admin |
| Invitation : réponse possible uniquement avant le début de la réunion | `now() < date_debut` |
| Invitation : seul l'utilisateur invité peut répondre | Vérifié par `participant_id` |

### Lancer l'application

```bash
php -S localhost:2000 -t public/
```

URL : `http://localhost:2000`

### Réinitialiser la base de données (si besoin)

```bash
php artisan migrate:fresh --seed
```
