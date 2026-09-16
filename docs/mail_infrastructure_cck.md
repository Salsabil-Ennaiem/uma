# Mail infrastructure — CCK (Plateforme UMA)

> Émetteur : éditeur · Destinataire : CCK (Centre de Calcul Khawarizmi) / exploitation RNU.
> Voir `docs/DECISIONS_UMA.md` ADR-0001 : ces réponses bornent le schéma (préfixe) et le lot FR/AR
> (index sur colonnes générées), sans bloquer le développement en cours.

## Objet
Plateforme UMA — Précisions d'infrastructure pour la préparation du déploiement

## Corps

Bonjour,

Dans le cadre du déploiement de la plateforme UMA (recette puis mise en production), trois
précisions d'infrastructure sont nécessaires pour préparer la base de données et
l'environnement d'exécution :

1. **Schéma / préfixe des tables** : la plateforme disposera-t-elle d'un **schéma MySQL dédié**,
   ou devra-t-elle coexister avec les tables d'autres applications dans le même schéma ? Dans ce
   second cas, une convention de préfixe de tables est-elle imposée (par exemple `uma_…`) ?
2. **Versions fournies** : quelle version de **MySQL ou MariaDB**, et quelle version de **PHP**,
   est servie par le CCK ?
3. **Sauvegarde / restauration** : existe-t-il des contraintes particulières à anticiper
   (fréquence des sauvegardes, procédures ou outillage de restauration imposés, volumes attendus) ?

Ces réponses nous permettent d'arrêter le nom de la base (`uma_plateforme`), d'appliquer la
convention d'évitement de collision si elle existe, et de valider nos choix d'indexation.
Elles ne bloquent pas le développement en cours.

Merci d'avance.

Cordialement,
[Éditeur] — Plateforme UMA

## Notes de transmission
- Point 1 → branche ADR-0001 : Plan A (schéma dédié, sans préfixe) ou Plan B-V2 (préfixe global
  `uma_` + `prefix_indexes`, ~1 j).
- Point 2 → branche l'Annexe B de DECISIONS_UMA.md (matrice de conséquences SGBD pour le lot
  FR/AR : MySQL ≥5.7 / MySQL 8 / MariaDB ≥10.2 / ancien).
- Point 3 → documente les scripts externes (reporting, backup/restore) à signaler en livraison
  (changement `migrations` → `uma_migrations` en cas de Plan B).