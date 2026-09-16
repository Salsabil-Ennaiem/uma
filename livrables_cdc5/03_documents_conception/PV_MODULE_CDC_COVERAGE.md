# Couverture du CDC (Lot 2 — Plateforme doctorale) par `salsabil-ennaiem/pv-module`

> Ce document fait le point : quelles fonctionnalités du `Cdc.md` sont **déjà réalisables** avec
> le package `salsabil-ennaiem/pv-module`, ce qu'il faut **encore extraire de Voyager** pour s'en rapprocher,
> et si l'on peut considérer que le package contient **tout** le nécessaire pour les
> fonctionnalités « document / processus / archive » du cahier des charges.

--- 

## 1. Positionnement du module

`salsabil-ennaiem/pv-module` est un **noyau autonome** :
- **Génération de documents PDF** (mPDF : A4, UTF-8/dejavusans, FR/AR auto),
- **Workflow de validation à étapes** (brouillon → en_attente → valide/rejete, règles d'approbation paramétrables),
- **Signatures** (upload fichier ou dessin canvas, intégrées au PDF),
- **Notifications** mail + base à chaque changement d'état,
- **Templates** de document (par défaut et par utilisateur : sections, marges, orientation),
- **Versions, historique, duplication** et **archivage** des documents,
- Développé sans aucune dépendance vers l'hôte (contrats `CanManagePv`, `ApprovalRules`, `ParticipantResolver`), intégrable dans n'importe quelle app Laravel.

---

## 2. Fonctionnalités du CDC réalisables dès aujourd'hui avec le module

| Réf. CDC | Fonctionnalité demandée | Couverture par le module |
|---|---|---|
| 1.5 / 557-584 | PV de réunion de commission (saisie décisions, génération de PV) | ✅ `PvService::store`, template `pv_template.blade.php`, PDF `PdfService` |
| 1.5 / 552 | « Validation des PV par les personnes ayant assisté à la réunion » | ✅ cycle `en_attente` → `valide`, 1 vote = 1 entrée `pv_module_pv_validations` ; règles (`ApprovalRules`) paramétrables |
| 0.28 / 1.14 | Traçabilité des validations et des opérations | ✅ versions (`versions` json), `created_by`/`updated_by`, validations datées, historique complet par PV |
| 0.32 / 1.7 | PV de soutenance archivés | ✅ chaque PV généré + archivé (statuts, versions) ; PDF stocké sur disque configurable |
| 0.35 | Notifications automatiques à chaque changement d'état | ✅ notifications `mail` + `database` (`PvValidationRequest`, `PvValidated`) |
| 1.12 | Envoi d'e-mails (validation, notification) | ✅ mail transactionnel du workflow (envoi lors de send / validate) |
| 1.13 | Génération de PV, attestations — format HTML/PDF | ✅ rendu HTML (aperçu) ET PDF (mPDF) pour tout document |
| 1.13 / 724-736 | Marges, orientation, papier en tête par template | ✅ `PvTemplate` : config (marges), orientation, sections ordonnées header/middle/signature |
| 1.14 | Historique du parcours (documentaire) | ✅ duplication + snapshot + versions sur modification/annulation |
| 1.2 / 292-322 | Documents rattachés à un dossier (mallette) | ✅ colonnes polymorphes `source_type`/`source_id` : un document peut être rattaché à une thèse, un dossier, une demande… |
| 1.16+ | Langues FR/AR | ✅ génération bilingue via mPDF (`autoArabic` + dejavusans) |
| 0.23-0.35 | Réduction délais, traçabilité, uniformisation | ✅ workflow uniformisé + statuts + steps obligatoires (signature du créateur, participants, deadline) |

---

## 3. Fonctionnalités « document/process/archive » NON encore couvertes (à faire)

| Réf. CDC | Manque | Effort estimé |
|---|---|---|
| 1.13 | **Impression en masse (batch)** de documents | Extension `PdfService` (boucle + zip) |
| 1.13 | **Logos / papier à en-tête** paramétrables par structure | Extension config + template |
| 1.7 / 1.13 | Types de documents variés : **diplôme MESRS, arrêtés, décisions, invitations officielles, attestations** | Généraliser le module en « document engine » à types configurables (aujourd'hui : type `pv` uniquement) |
| 1.14 | **Archivage des pièces/justificatifs** uploadés (reçus de paiement, conventions, rapports) | Nouvelle entité « pièces » rattachées (upload générique, l'upload existe déjà via signatures) |
| 0.28 / 557-580 | **Journal d'audit / historique complet des modifications de réunion** | Extension (table de log d'audit) |
| 1.11 / 1.14 | **Exports Excel** des listes/décisions | Hors module (concernera les modules métier ; Voyager dispose déjà d'outils d'export) |
| 0.30-0.35 | **Workflows métier complets** (inscriptions/renouvellements/dérogations, encadrement, cotutelle, soutenances, crédits, bourses) | **Nouvelles applications** — mais chacune pourra s'appuyer sur ce package comme moteur documentaire et de validation |

---

## 4. Ce qu'on peut encore extraire de Voyager pour se rapprocher du CDC

Voyager contient déjà un gros socle métier inutilisé par le package. Extractions recommandées, par priorité :

1. **Moteur PDF élargi** — `app/Services/PdfService.php`, `app/Controllers/PdfController.php`, `resources/views/pdfs/*` (`pdf-builder.blade.php`, `export_reunion.blade.php`).
   → apporte : templates PDF multiples, en-têtes/pieds, exports batch/listes, format varié.
2. **Gestion de réunions** — `app/Models/Reunion.php`, `app/Models/Invitation.php`, `app/Models/Organism.php`, `app/Models/OrganismType.php`.
   → apporte : ordres du jour, invitations officielles imprimables, salle/jury, planification.
3. **Notifications & mails riches** — `app/Services/NotificationService.php`, `app/Mail/*` (`PVValidationRequestMail`, `PVValidatedMail`, `ExcuseResponseMail`, `ExcuseSubmittedMail`), templates Mailable.
   → apporte : mails HTML structurés multi-contextes (exigence 1.12).
4. **Politiques/ACL** — `app/Policies/PVPolicy` (et possibilités de purger/sponsorier le contrat `CanManagePv`).
5. **Assets front** — `public/js/pdf-builder.js`, `signature.js`, `participant-management.js`.
   → apporte : éditeur visuel de sections, gestion dynamique des participants (parts 1.5 / réunions).
6. **Export Excel** — module `maatwebsite/excel` probable + code d'export existant (écrans intelligents 6.1).

> Avec ces extractions, on couvre l'essentiel du **volet « rapports et états » (1.13), « archivage » (1.14),
> « communications » (1.12)** et tout le documentaire des soutenances/réunions/inscriptions.

---

## 5. Verdict : le package contient-il « tout » pour le document/process/archive du CDC ?

**Oui, sur le plan du « moteur »** : le package contient aujourd'hui toutes les briques
**documentaires et de processus** nécessaires à la plateforme :
génération PDF (FR/AR), workflow de validation multi-acteurs avec règles, preuves de validation,
signatures électroniques intégrées au document, templates configurables, versioning, archivage,
traçabilité et notifications. C'est le **backbone** que tous les modules métier (inscriptions,
soutenances, réunions, crédits…) devront utiliser pour générer/archiver leurs documents.

**Non, sur le plan du « contenu » et du « métier »** :
1. les **types de documents spécifiques** du CDC (attestations, arrêtés, décisions, invitations,
   diplôme MESRS) ne sont pas encore modélisés — il faut **généraliser le module** en
   « document engine » à types configurables (faible effort, la structure template + PDF + workflow est prête) ;
2. **les modules métier** (candidatures, inscriptions années 1→5, crédits, bourses, cotutelles,
   soutenances, réclamations/ticketing, statistiques, front-office/CMS) **ne font pas partie**
   de ce package et doivent exister comme applications qui consommeront ce moteur documentaire.

### Recommandation
Étendre `salsabil-ennaiem/pv-module` vers un **« Document & Workflow Engine » générique** :
- types de documents paramétrables (`pv`, `attestation`, `arrete`, `decision`, `invitation`, `diplome`…),
- batch d'impression, logos/en-têtes paramétrables, archivage de pièces, journal d'audit,
- lignes directrices : toutes les fonctions CDC « (1.13) rapports/états », « (1.14) archivage »,
  « (1.12) communications » et les deux processus documentaires (réunions, PV de soutenance)
  seront alors couverts par ce package seul. Le reste du CDC (fonctionnalités métier) viendra
  en modules consommateurs.