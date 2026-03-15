# Base de données

Ce dossier contient le schéma SQL du projet WE4-SI40.

## Fichiers

- `schema.sql` : Export complet de la base de données (structure + données de test)

## Importer la base de données

### Via phpMyAdmin :
1. Ouvrir phpMyAdmin (`http://localhost/phpmyadmin`)
2. Créer une nouvelle base de données avec le même nom
3. Sélectionner la base → onglet **Importer**
4. Choisir le fichier `schema.sql` → cliquer **Exécuter**

### Via la ligne de commande (XAMPP) :
```bash
mysql -u root -p nom_de_la_base < database/schema.sql
```

## Mettre à jour le fichier SQL

À chaque modification du schéma (nouvelles tables, colonnes, etc.), réexporter la base depuis phpMyAdmin et remplacer `schema.sql`, puis faire un commit Git.
