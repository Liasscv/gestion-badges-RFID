Gestion d'accès par badge RFID
Bienvenue sur ce projet pédagogique de gestion d’accès sécurisé par badge RFID, réalisé dans le cadre du BTS CIEL, option Informatique et Réseaux.

L'objectif est de développer et démontrer une solution complète de contrôle d’accès, de supervision vidéo, de traçabilité et de gestion centralisée, en réponse aux besoins d’un établissement scolaire type lycée.

Fonctionnement général
Le système permet à chaque utilisateur (élève, visiteur, personnel) d’être identifié grâce à un badge RFID, dont la validation donne accès à des zones sécurisées.
Le module Barionet100 se charge d’actionner l’ouverture des portes ou autres dispositifs, sur ordre du serveur, tout en assurant la compatibilité avec les capteurs et actionneurs existants.

L'ensemble du matériel — lecteurs RFID (Inveo, Minova), caméra IP pour la supervision en temps réel, postes d’accueil, Barionet, serveur informatique — est interconnecté via le réseau Ethernet du lycée afin de garantir instantanéité et sécurité.

Interface web et rôles
Le serveur central héberge l’interface web d’administration développée en PHP/MySQL.

Agent d’accueil :
Dispose d’un tableau de bord dédié permettant de visualiser en direct le flux caméra pour identifier les visiteurs, autoriser ou refuser un accès manuellement, ou encore consulter l’historique des entrées et sorties.

Technicien :
Accède à une interface de gestion avancée pour gérer les badges, utilisateurs et droits, configurer le matériel réseau (adresses IP, modes de fonctionnement automatisés ou manuels), et superviser en détail tous les événements et états du système.

Chaque action, présentation de badge, ouverture de porte, autorisation manuelle, changement de mode, etc. est enregistrée dans une base SQL sécurisée, assurant la traçabilité et la sécurité des accès.

