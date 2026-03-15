# Projet pour les UE WE4A, WE4B et SI40

Thème du site : **Site de coaching (développement personnel : réaliser ses rêves / passer à l'action)**

Vous pouvez ajouter tout ce que vous voulez, mais l’idée principale est la suivante :

**Objectif principal** : Le client doit être incité à prendre un rendez-vous préliminaire gratuit.

# Fonctionnalités souhaitées (niveau basique)

## Accueil :

Mise en avant du besoin du client

Utilisation de témoignages d’anciens clients

## Réservation de rendez-vous

Possibilité de réserver un rendez-vous classique

Possibilité de réserver un rendez-vous préliminaire gratuit sans avoir besoin de se connecter → Cette fonctionnalité doit être mise en avant (objectif : le client arrive sur le site et peut réserver facilement)

# Fonctionnalités liées aux UE

Inscription

Connexion

Différents types d’utilisateurs : Admin, utilisateur classique, etc.

ect...

http://localhost/WE4-SI40/

## Requêtes SQL pour la base de données :

CREATE TABLE utilisateur (
id_utilisateur INT NOT NULL AUTO_INCREMENT,
email VARCHAR(255) NOT NULL UNIQUE,
nom VARCHAR(100) NOT NULL,
prenom VARCHAR(100) NOT NULL,
mot_de_passe VARCHAR(255) NOT NULL,
date_naissance DATE NOT NULL,
admin TINYINT(1) NOT NULL DEFAULT 0,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_utilisateur)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
