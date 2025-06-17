<?php
$code_badge = htmlspecialchars($_GET["UID"]);
$dev_id = htmlspecialchars($_GET["devID"]);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acl";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Requête simple pour vérifier l'existence du badge
    $sql = "SELECT b.code_badge, u.nom, u.prenom 
            FROM badge b
            JOIN utilisateur u ON b.id_utilisateur = u.id_utilisateur
            WHERE b.code_badge = :code_badge AND b.etat = 1";
    
    $reponse = $conn->prepare($sql);
    $reponse->bindValue(':code_badge', $code_badge, PDO::PARAM_STR);
    $reponse->execute();
    
    if ($reponse->rowCount() > 0) {
        $badge_info = $reponse->fetch(PDO::FETCH_ASSOC);
        
        // Activation du Barionet
        $barionet_ip = "172.22.21.164"; // Adresse IP du Barionet
        $urlOn = "http://$barionet_ip/rc.cgi?o=1,10";
        file_get_contents($urlOn);
        
        // Message de bienvenue simple
        echo $dev_id.',LCDCLR,LCDSET;0;5;3;Badge valide,LCDSET;0;25;3;Acces autorise';
    } else {
        echo $dev_id.',LCDCLR,LCDSET;0;10;0;Badge invalide';
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    echo $dev_id.',LCDCLR,LCDSET;0;10;0;ERREUR BD';
    error_log("Erreur de base de données : " . $e->getMessage());
}
?>
