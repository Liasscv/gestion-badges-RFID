<?php
session_start();

// Détruire toutes les données de la session
session_unset();     // Supprime toutes les variables de session
session_destroy();   // Détruit la session

// Rediriger vers la page de connexion
header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html");  // Remplace par le chemin correct vers ta page de connexion
exit();
?>
