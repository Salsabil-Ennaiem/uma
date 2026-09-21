# ADR-0006 — Organigramme jsOrgChart + calendrier reunions (slots)

## Contexte
- Demande : sidebar Filament « Organigramme » hierarchique
  (universite > ecole doctorale > etablissement > commission > membre),
  clic = detail, edition seulement si policy `update` OK (forms des
  Resources existants reutilises).
- Demande : `wooserv/filament-date-time-slots` (deja dans composer.json)
  pour choisir date puis creneau (heures ouvrees), creation depuis bouton
  Filament ou slot, vue calendrier + modification/suppression.

## Decision
- `jsOrgChart` integre **en local** sans CDN : rendu inline dans la blade
  (`wire:ignore` + `$wire.call('sel', ...)`, API `nodes:[{id,parent,type,
  modelId,name,title}]`) ; copie de secours `resources/js/org-chart/js-orgchart.js`
  (+ `public/js/org-chart/` si besoin asset direct).
- Page Filament auto-decouverte : `App\Filament\Pages\Organigramme`
  (`resources/views/filament/pages/organigramme.blade.php`), groupe
  « Institution », route `/admin/organigramme`.
- Selection controlee par policies existantes : `InstitutionPolicy`
  (universite / ecole / etablissement : lecture cadres, ecriture admin),
  `CommissionPolicy` + `UserPolicy` (membres), toutes mappees dans
  `AppServiceProvider`. Aucune policy dupliquee : les anciens fichiers
  `UniversitePolicy` / `EcoleDoctoralePolicy` / `EtablissementPolicy`
  sont de simples alias `extends InstitutionPolicy` (compatibilite).
- Reunions : `ReunionForm` utilise `DateTimeSlotPicker` wooserv sur `date_debut`
  (format `Y-m-d H:i`, `minDate(now())`, `minimumLeadTime(30)`,
  `slotInterval(30)`, Lun–Ven 08:00–18:00 + Sam 09:00–12:00, creneaux
  occupes affiches via `blockedSlots`, `date_fin` (DateTimePicker) auto +2h).
- Calendrier : `App\Filament\Pages\CalendrierReunions` (mois, groupe
  « Reunions », `/admin/calendrier-reunions`) : bouton header « Creer une
  reunion » (policy `create`, pre-remplit `?jour=YYYY-MM-DD` depuis le jour
  clique), clic jour/reunion = panneau lateral, « Modifier » (policy
  `update`, page edit Resource), « Supprimer » (policy `delete`).

## Verification
- `php artisan test` a executer quand PHP dispo (shell bloque dans
  l'environnement agent : a relancer par l'utilisateur).
- Recette : sidebar Organigramme visible, clic nœud = detail,
  edition refusee sans droit, slot picker visible en create/edit reunion,
  calendrier affiche/modifie/supprime selon droits.
