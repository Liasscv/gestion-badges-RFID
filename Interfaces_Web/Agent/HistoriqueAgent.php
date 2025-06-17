<?php
session_start();
require '../config.php';
// Redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html");
    exit();
}
try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $pdo_options);
} catch (PDOException $e) {
    die("<p style='color: red;'>Erreur de connexion : " . $e->getMessage() . "</p>");
}

// Récupération des paramètres de filtrage
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';
$date_filtre = isset($_GET['date_filtre']) ? $_GET['date_filtre'] : '';
$heure_filtre = isset($_GET['heure_filtre']) ? $_GET['heure_filtre'] : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;  // Par défaut, limite à 50 passages

// Construction de la condition WHERE
$conditions = [];
$binds = [];

if (!empty($recherche)) {
    $conditions[] = "(u.nom LIKE :recherche 
                   OR u.prenom LIKE :recherche 
                   OR b.code_badge LIKE :recherche
                   OR r.nom LIKE :recherche)";
    $binds[':recherche'] = "%$recherche%";
    
    if (strtolower($recherche) === 'accepté') {
        $conditions[] = "h.statut = 1";
    } elseif (strtolower($recherche) === 'refusé') {
        $conditions[] = "h.statut = 0";
    }
}

if (!empty($date_filtre)) {
    $conditions[] = "h.date = :date_filtre";
    $binds[':date_filtre'] = $date_filtre;
}

if (!empty($heure_filtre)) {
    // Modification : affiche tous les passages avant ou à l'heure spécifiée
    $conditions[] = "h.heure <= :heure_filtre";
    $binds[':heure_filtre'] = $heure_filtre;
}

// Construction de la clause WHERE
$where_clause = '';
if (!empty($conditions)) {
    $where_clause = "WHERE " . implode(' AND ', $conditions);
}

// Requête pour compter le nombre total d'enregistrements (sans limite)
$query_count = "SELECT COUNT(*) 
                FROM historique h
                JOIN badge b ON h.id_badge = b.id_badge
                JOIN utilisateur u ON b.id_utilisateur = u.id_utilisateur
                JOIN role r ON u.id_role = r.id_role
                $where_clause";

$stmt_count = $pdo->prepare($query_count);
foreach ($binds as $key => $value) {
    $stmt_count->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt_count->execute();
$total_count = $stmt_count->fetchColumn();

// Requête pour récupérer les données avec la limite
$query = "SELECT h.id_historique, u.nom, u.prenom, b.code_badge, h.date, h.heure, h.statut, r.nom AS nom_role
          FROM historique h
          JOIN badge b ON h.id_badge = b.id_badge
          JOIN utilisateur u ON b.id_utilisateur = u.id_utilisateur
          JOIN role r ON u.id_role = r.id_role
          $where_clause
          ORDER BY h.date DESC, h.heure DESC
          LIMIT :limit";

$stmt = $pdo->prepare($query);
foreach ($binds as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$historique = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nombre d'enregistrements affichés actuellement
$total_affiches = count($historique);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des passages</title>
    <link rel="stylesheet" href="historique.css">
    <style>
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            align-items: flex-end;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .limit-selector {
            margin-left: auto;
        }
    </style>
</head>
<body>
    <h1>Historique des passages</h1>

    <div class="filter-container">
        <form method="get" action="" class="filter-form">
            <div class="filter-group">
                <label for="recherche">Recherche :</label>
                <input type="text" id="recherche" name="recherche" value="<?= htmlspecialchars($recherche); ?>" 
                       placeholder="Nom, prénom, badge...">
            </div>
            
            <div class="filter-group">
                <label for="date_filtre">Date :</label>
                <input type="date" id="date_filtre" name="date_filtre" value="<?= htmlspecialchars($date_filtre); ?>">
            </div>
            
            <div class="filter-group">
                <label for="heure_filtre">Heure (jusqu'à) :</label>
                <input type="time" id="heure_filtre" name="heure_filtre" value="<?= htmlspecialchars($heure_filtre); ?>">
            </div>
            
            <div class="filter-group limit-selector">
                <label for="limit">Nombre de passages :</label>
                <select id="limit" name="limit">
                    <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= $limit == 200 ? 'selected' : '' ?>>200</option>
                    <option value="500" <?= $limit == 500 ? 'selected' : '' ?>>500</option>
                    <option value="1000" <?= $limit == 1000 ? 'selected' : '' ?>>1000</option>
                </select>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="HistoriqueAgent.php" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>

    <?php if (empty($historique)): ?>
        <p style="text-align: center;">Aucun passage enregistré correspondant aux critères.</p>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôle</th>
                        <th>Numéro de badge</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historique as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['prenom']) ?></td>
                            <td><?= htmlspecialchars($row['nom_role']) ?></td>
                            <td><?= htmlspecialchars($row['code_badge']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['date'])) ?></td>
                            <td><?= htmlspecialchars($row['heure']) ?></td>
                            <td class="<?= intval($row['statut']) === 1 ? 'statut-accepte' : 'statut-refuse' ?>">
                                <?= intval($row['statut']) === 1 ? 'Accepté' : 'Refusé' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <footer>
        <p>Affichage: <?= $total_affiches ?> sur <?= $total_count ?> enregistrement(s) trouvé(s)</p>
        <a href="espace_agent.php" class="retour-btn">Retour à la page d'accueil</a>
    </footer>
</body>
</html>