<?php
$code_badge = htmlspecialchars($_GET["UID"]);
$dev_id = htmlspecialchars($_GET["devID"]);
require '../config.php';

try {
    $conn = new PDO(DB_DSN, DB_USER, DB_PASS, $pdo_options);

    // 1. Vérification du badge + récupération utilisateur + rôle (changer b.id_utilisateur si besoin)
    $sql = "SELECT b.id_badge, u.id_utilisateur, u.nom, u.prenom, u.id_role
            FROM badge b
            JOIN utilisateur u ON b.id_utilisateur = u.id_utilisateur
            WHERE b.code_badge = :code_badge AND b.etat = 1";
    $reponse = $conn->prepare($sql);
    $reponse->bindValue(':code_badge', $code_badge, PDO::PARAM_STR);
    $reponse->execute();
    
    if ($reponse->rowCount() > 0) {
        $badge_info = $reponse->fetch(PDO::FETCH_ASSOC);
        $id_badge = $badge_info['id_badge'];
        $id_role = $badge_info['id_role'];

        // 2. Récupérer les horaires via id_role (clé étrangère)
        $sqlHoraire = "SELECT heure_debut, heure_fin FROM role WHERE id_role = :id_role";
        $getHoraire = $conn->prepare($sqlHoraire);
        $getHoraire->bindValue(':id_role', $id_role, PDO::PARAM_INT);
        $getHoraire->execute();
        $horaire = $getHoraire->fetch(PDO::FETCH_ASSOC);

        $heureActuelle = date("H:i:s");

        // 3. Vérifier l'accès horaire
        if ($horaire && $heureActuelle >= $horaire['heure_debut'] && $heureActuelle <= $horaire['heure_fin']) {
            // Insertion historique (statut accepté)
            $insert = $conn->prepare("INSERT INTO historique (id_badge, date, heure, statut) VALUES (:id_badge, CURDATE(), CURTIME(), 1)");
            $insert->bindValue(':id_badge', $id_badge, PDO::PARAM_INT);
            $insert->execute();

            // Activation du Barionet
            $barionet_ip = "172.22.21.164";
            $urlOn = "http://$barionet_ip/rc.cgi?o=1,10";
            file_get_contents($urlOn);

            echo $dev_id.',LCDCLR,LCDSET;0;13;5;Badge valide,LCDSET;8;35;3;Acces autorise';
        } else {
            // Insertion historique (statut refusé - hors horaires)
            $insert = $conn->prepare("INSERT INTO historique (id_badge, date, heure, statut) VALUES (:id_badge, CURDATE(), CURTIME(), 0)");
            $insert->bindValue(':id_badge', $id_badge, PDO::PARAM_INT);
            $insert->execute();

            echo $dev_id.',LCDCLR,LCDSET;0;13;4;Hors horaires,LCDSET;8;35;3;Acces refuse';
        }
    } else {
        // Badge inconnu ou désactivé (code comme avant)
        $getId = $conn->prepare("SELECT id_badge FROM badge WHERE code_badge = :code_badge");
        $getId->bindValue(':code_badge', $code_badge, PDO::PARAM_STR);
        $getId->execute();
        $id_badge = $getId->fetchColumn();
        if ($id_badge) {
            $insert = $conn->prepare("INSERT INTO historique (id_badge, date, heure, statut) VALUES (:id_badge, CURDATE(), CURTIME(), 0)");
            $insert->bindValue(':id_badge', $id_badge, PDO::PARAM_INT);
            $insert->execute();
        }
        echo $dev_id.',LCDCLR,LCDSET;0;13;4;Badge invalide,LCDSET;8;35;3;Acces refuse';
    }
} catch (PDOException $e) {
    echo $dev_id.',LCDCLR,LCDSET;0;10;0;ERREUR BD';
    error_log("Erreur de base de données : " . $e->getMessage());
}
?>