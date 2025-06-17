<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html");
    exit();
}

try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $pdo_options);
} catch (PDOException $e) {
    die("<p>Erreur de connexion : " . $e->getMessage() . "</p>");
}

if (isset($_POST['desactiver_badge'])) {
    $id_badge = $_POST['id_badge'];
    try {
        $stmt = $pdo->prepare("UPDATE badge SET etat = 0 WHERE id_badge = ?");
        $message = $stmt->execute([$id_badge]) ? "Le badge a été désactivé avec succès." : "Erreur lors de la désactivation du badge.";
        $messageType = $stmt->execute([$id_badge]) ? "success" : "error";
    } catch (PDOException $e) {
        $message = "Erreur: " . $e->getMessage();
        $messageType = "error";
    }
}

if (isset($_POST['reactiver_badge'])) {
    $id_badge = $_POST['id_badge'];
    try {
        $stmt = $pdo->prepare("UPDATE badge SET etat = 1 WHERE id_badge = ?");
        $message = $stmt->execute([$id_badge]) ? "Le badge a été réactivé avec succès." : "Erreur lors de la réactivation du badge.";
        $messageType = $stmt->execute([$id_badge]) ? "success" : "error";
    } catch (PDOException $e) {
        $message = "Erreur: " . $e->getMessage();
        $messageType = "error";
    }
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'actif';
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
$condition_recherche = '';
$binds = [];

if (!empty($recherche)) {
    $condition_recherche = " AND (u.nom LIKE :recherche 
                             OR u.prenom LIKE :recherche 
                             OR b.code_badge LIKE :recherche 
                             OR b.id_badge LIKE :recherche)";
    $binds[':recherche'] = "%" . $recherche . "%";
}

switch ($filter) {
    case 'actif':
        $whereClause = "WHERE b.etat = 1";
        break;
    case 'inactif':
        $whereClause = "WHERE b.etat = 0";
        break;
    default:
        $whereClause = "WHERE 1=1";
        break;
}

$query = "
    SELECT b.id_badge, b.code_badge, b.etat, u.nom, u.prenom 
    FROM badge b
    JOIN utilisateur u ON b.id_utilisateur = u.id_utilisateur
    $whereClause
    $condition_recherche
    ORDER BY u.nom, u.prenom
";

$stmt = $pdo->prepare($query);
foreach ($binds as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->execute();
$badges = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_badges = count($badges);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Badges</title>
    <link rel="stylesheet" href="GestionBadges.css">
</head>
<body>
    <h2>Gestion des Badges</h2>

    <?php if (isset($message)): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="options">
        <form method="get" action="">
            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
            <label for="recherche">Rechercher :</label>
            <input type="text" id="recherche" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>" 
                   placeholder="Nom, prénom ou code badge...">
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div class="filter-section">
        <h3>Liste des badges</h3>
        <div class="filter-tabs">
            <a href="?filter=actif<?php echo !empty($recherche) ? '&recherche='.urlencode($recherche) : ''; ?>" 
               class="<?php echo $filter == 'actif' ? 'active' : ''; ?>">Badges actifs</a>
            <a href="?filter=inactif<?php echo !empty($recherche) ? '&recherche='.urlencode($recherche) : ''; ?>" 
               class="<?php echo $filter == 'inactif' ? 'active' : ''; ?>">Badges inactifs</a>
            <a href="?filter=tous<?php echo !empty($recherche) ? '&recherche='.urlencode($recherche) : ''; ?>" 
               class="<?php echo $filter == 'tous' ? 'active' : ''; ?>">Tous les badges</a>
        </div>
    </div>

    <?php if (empty($badges)): ?>
        <p>Aucun badge trouvé correspondant aux critères de recherche.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Code Badge</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($badges as $badge): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($badge['code_badge']); ?></td>
                            <td><?php echo htmlspecialchars($badge['nom']); ?></td>
                            <td><?php echo htmlspecialchars($badge['prenom']); ?></td>
                            <td class="<?php echo $badge['etat'] == 1 ? 'statut-actif' : 'statut-inactif'; ?>">
                                <?php echo $badge['etat'] == 1 ? 'Actif' : 'Désactivé'; ?>
                            </td>
                            <td>
                                <?php if ($badge['etat'] == 1): ?>
                                    <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver ce badge?');">
                                        <input type="hidden" name="id_badge" value="<?php echo $badge['id_badge']; ?>">
                                        <button type="submit" name="desactiver_badge" class="deactivate">Désactiver (Badge perdu)</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir réactiver ce badge?');">
                                        <input type="hidden" name="id_badge" value="<?php echo $badge['id_badge']; ?>">
                                        <button type="submit" name="reactiver_badge" class="activate">Réactiver le badge</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <footer>
        <p>Total: <?php echo $total_badges; ?> badge(s)</p>
        <p><a href="espace_technicien.php" class="retour-btn">Retour à la page d'accueil</a></p>
    </footer>
</body>
</html>
