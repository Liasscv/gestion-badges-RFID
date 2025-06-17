<?php
session_start();

// Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Technicien</title>
    <link rel="stylesheet" href="espace_technicien.css">
</head>
<body>
    
    <div class="container">
        <div class="wrapper">
            <h1>Espace Technicien</h1>
            <ul>
            <li>
                <p><a href="HistoriqueTechnicien.php">Historique des passages</a></p>
            </li>
            <li>
                <p><a href="AjouterMembre.php">Ajouter un membre</a></p>
            </li>
            <li>
                <p><a href="SupprimerMembre.php">Supprimer un membre</a></p>
            </li>
            <li>
                <p><a href="GestionBadges.php">Gestion des badges</a></p>
            </li>
            </ul>
            <a href="deconnexion.php" class="button-danger">Se déconnecter</a>
        </div>
    </div>        
</body>
</html>