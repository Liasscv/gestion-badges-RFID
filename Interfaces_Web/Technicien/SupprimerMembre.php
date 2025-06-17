<?php
session_start();
require '../config.php'; // adapte le chemin si besoin

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
    <title>Supprimer un membre</title>
    <link rel="stylesheet" href="SupprimerMembre.css">
</head>
<body>
    <h2>Supprimer un membre</h2>
    <?php
    // Connexion à la base de données avec PDO via les constantes du config.php
    try {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $pdo_options);
    } catch (PDOException $e) {
        die("<p>Erreur de connexion : " . $e->getMessage() . "</p>");
    }

    // Récupérer tous les membres disponibles
    $membres = [];
    $stmt = $pdo->query("SELECT u.id_utilisateur, u.nom, u.prenom, r.nom as role 
                         FROM utilisateur u 
                         JOIN role r ON u.id_role = r.id_role
                         ORDER BY u.nom, u.prenom");
    while ($row = $stmt->fetch()) {
        $membres[$row['id_utilisateur']] = $row['nom'] . ' ' . $row['prenom'] . ' (' . $row['role'] . ')';
    }

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_utilisateur'])) {
        $id_utilisateur = $_POST['id_utilisateur'];
        
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
            $stmt->execute([$id_utilisateur]);
            
            $pdo->commit();
            echo "<p style='color: green;'>Membre supprimé avec succès.</p>";

            // Rafraîchir la liste des membres
            $stmt = $pdo->query("SELECT u.id_utilisateur, u.nom, u.prenom, r.nom as role 
                                FROM utilisateur u 
                                JOIN role r ON u.id_role = r.id_role
                                ORDER BY u.nom, u.prenom");
            $membres = [];
            while ($row = $stmt->fetch()) {
                $membres[$row['id_utilisateur']] = $row['nom'] . ' ' . $row['prenom'] . ' (' . $row['role'] . ')';
            }
            
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<p style='color: red;'>Erreur lors de la suppression : " . $e->getMessage() . "</p>";
        }
    }
    ?>
    <!-- Formulaire -->
    <form method="post">
        <label for="id_utilisateur">Sélectionner un membre à supprimer :</label>
        <select id="id_utilisateur" name="id_utilisateur" required>
            <option value="">-- Sélectionner un membre --</option>
            <?php
            foreach ($membres as $id => $description) {
                echo "<option value=\"$id\">$description</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?');">
    </form>
    <p><a href="espace_technicien.php">Retour à la page d'accueil</a></p>
</body>
</html>
