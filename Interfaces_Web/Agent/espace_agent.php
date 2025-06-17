<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    // Rediriger vers la page de connexion
    header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html?error=acces_non_autorise");
    exit();
}

// Vérifier que l'utilisateur a le rôle Agent
if ($_SESSION['user_role'] != 'Agent') {
    header("Location: ../Index.html?error=acces_non_autorise");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Agent</title>
    <link rel="stylesheet" href="espace_agent.css">
</head>
<body>
   
    <div class="container">
        <div class="wrapper">
            <h1>Espace Agent</h1>
            <ul>
            <li>
                <p><a href="Camera.php">Caméra IP/Gestion d'accès</a></p>
            </li>
            <li>
                <p><a href="HistoriqueAgent.php">Historique des passages</a></p>
            </li>
            </ul>
            <a href="deconnexion.php" class="button-danger">Se déconnecter</a>
        </div>
    </div>        
</body>
</html>