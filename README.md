# Gestion d'accès par Badge RFID

Projet pédagogique pour la gestion d'accès sécurisée par badge RFID, réalisé dans le cadre du BTS CIEL option Informatique et Réseau.

## Présentation générale

Ce projet offre une solution centralisée de contrôle d'accès, de supervision vidéo, de traçabilité et de gestion des événements pour un établissement scolaire type lycée. Les modules matériels et logiciels facilitent l'attribution des droits, la gestion des accès et la supervision en temps réel.

## Fonctionnalités principales

**Gestion des utilisateurs et des rôles**
Les utilisateurs (élèves, visiteurs, personnel) disposent de badges RFID. Chaque badge donne accès à des zones sécurisées selon le profil attribué.

**Supervision en temps réel**
Le système permet un contrôle instantané de l'état des accès, la gestion des autorisations, la validation des passages, et la gestion manuelle des accès exceptionnels.

**Traçabilité et historique des accès**
Tous les événements sont enregistrés dans une base de données SQL sécurisée, assurant la traçabilité complète des accès.

**Compatibilité matérielle**
Le module communique avec des lecteurs RFID (exemple : Invéo, Miwa, HID) et intègre la supervision de dispositifs tiers (caméras, portes...).

**Administration via interface web**
Une interface admin permet de gérer les utilisateurs, consulter les historiques et paramétrer le matériel (adresses IP, modes automatiques/manuels, accès caméras...).

## Diagrammes UML

![Diagramme d'architecture du projet](Diagrammes%20UML/DiagrammeDeDeploiment.png)

*Diagramme de déploiement du système RFID.*


### Diagramme de cas d'utilisation

![Diagramme de cas d'utilisation](Diagrammes%20UML/DiagrammeCasd_utilisation.png)
*Diagramme UML principal, visualisant les cas d'usage du système.*

## Technologies utilisées

- PHP / MySQL (pour l’API Web et la Base de données)
- HTML / CSS / JavaScript (pour l'interface d’administration)
- Matériel RFID (lecteurs Invéo, Miwa, HID, serveurs Barionet)
- Caméras IP (pour la supervision vidéo)

## Installation et déploiement

1. Cloner le dépôt sur le serveur ou poste de travail.
2. Installer les dépendances nécessaires (voir doc technique dans `Documentation MCR04`).
3. Configurer la base de données (importer le fichier SQL et adapter l’accès dans les fichiers de config).
4. Démarrer les services RFID et l’interface web.

## Utilisation

1. Connecter le matériel RFID et s’assurer que le système communique bien avec la base de données.
2. Accéder à l’interface d'administration pour enregistrer les utilisateurs et leur attribuer un badge.
3. Consulter les logs des accès via l’interface admin.
4. Superviser l’état des portes, des accès et des caméras en temps réel.

## Crédits

Projet réalisé par Elias COSME VINOU Romain METAIS et Akouman DIANGO pour le projet Accès Lycéee dans le cadre du BTS CIEL option Informatique et Réseaux, session 2024-2025.





