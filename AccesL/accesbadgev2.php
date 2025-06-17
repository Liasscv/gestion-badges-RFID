<?php
$id_badge = htmlspecialchars($_GET["UID"]);
$dev_id = htmlspecialchars($_GET["devID"]);

$host = "localhost";
$user = "Akou";
$pass = "02413280aA@@";
$db = 'acl-test';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM `acces` WHERE UID = :id_badge";
    $reponse = $conn->prepare($sql);
    $reponse->bindValue(':id_badge', $id_badge, PDO::PARAM_STR);
    $reponse->execute();

    if ($reponse->rowCount() > 0) {
        // Badge trouvé, envoyer la commande CGI pour activer la sortie 1 pendant 10 secondes
        $barionet_ip = "172.22.21.164"; // Adresse IP du Barionet
   
        // URL pour activer la sortie
        $urlOn = "http://$barionet_ip/rc.cgi?o=1,10";
		
        // Envoyer la commande pour activer la sortie
        file_get_contents($urlOn);

        echo $dev_id.',LCDCLR,LCDSET;0;5;3;Badge trouve,LCDSET;0;25;3;Bienvenue';
    } else {
        echo $dev_id.',LCDCLR,LCDSET;0;10;0;Aucun badge trouve';
    }
} catch (PDOException $e) {
    echo $dev_id.',LCDCLR,LCDSET;0;10;0;ERREUR';
}
?>