<?php
session_start();
require '../config.php';
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
    <title>Ajouter un membre</title>
    <link rel="stylesheet" href="AjouterMembre.css">
</head>
<body>
    <h2>Ajouter un nouveau membre</h2>
    <?php
    try {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $pdo_options);
    } catch (PDOException $e) {
        die("<p>Erreur de connexion : " . $e->getMessage() . "</p>");
    }
    // Récupérer tous les rôles disponibles pour les afficher dans le formulaire
    $roles = [];
    $stmt = $pdo->query("SELECT id_role, nom FROM role"); // Utilisation de "nom" au lieu de "nom_role"
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roles[$row['id_role']] = $row['nom'];
    }
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $code_badge = trim($_POST['code_badge']);
        $nom_role = trim($_POST['nom_role']); // Récupération du nom du rôle
        // Vérifier si tous les champs sont remplis
        if (!empty($nom) && !empty($prenom) && !empty($code_badge) && !empty($nom_role)) {
            
            // Vérifier si le code du badge existe déjà
            $stmt = $pdo->prepare("SELECT code_badge FROM badge WHERE code_badge = ?");
            $stmt->execute([$code_badge]);
            $badge_existant = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($badge_existant) {
                echo "<p style='color: red;'>Erreur : Ce code de badge existe déjà dans la base de données.</p>";
            } else {
                // Récupérer l'ID du rôle sélectionné
                $stmt = $pdo->prepare("SELECT id_role FROM role WHERE nom = ?");
                $stmt->execute([$nom_role]);
                $role = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$role) {
                    die("<p>Erreur : Le rôle spécifié n'existe pas.</p>");
                }
                $id_role = $role['id_role'];
                // Insérer l'utilisateur
                $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, id_role) VALUES (?, ?, ?)");
                if ($stmt->execute([$nom, $prenom, $id_role])) {
                    $id_utilisateur = $pdo->lastInsertId();
                    // Insérer le badge avec l'id_utilisateur et initialiser l'état à 1 (actif)
                    $stmt = $pdo->prepare("INSERT INTO badge (code_badge, id_utilisateur, etat) VALUES (?, ?, 1)");
                    if ($stmt->execute([$code_badge, $id_utilisateur])) {
                        echo "<p style='color: green;'>Utilisateur et badge ajoutés avec succès.</p>";
                    } else {
                        echo "<p style='color: red;'>Erreur lors de l'ajout du badge.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Erreur lors de l'ajout de l'utilisateur.</p>";
                }
            }
        } else {
            echo "<p style='color: red;'>Veuillez remplir tous les champs.</p>";
        }
    }
    ?>
    <!-- Formulaire pour ajouter un membre -->
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br><br>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br><br>
        <label for="code_badge">Code du badge :</label>
        <input type="text" id="code_badge" name="code_badge" required><br><br>
        <label for="nom_role">Rôle :</label>
        <select id="nom_role" name="nom_role" required>
            <option value="">-- Sélectionner un rôle --</option>
            <?php
            foreach ($roles as $id => $nom) {
                echo "<option value=\"$nom\">$nom</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Ajouter" onclick="return confirm('Êtes-vous sûr de vouloir ajouter ce membre ?')">
    </form>
    <p><a href="espace_technicien.php">Retour à la page d'accueil</a></p>
</body>
</html>