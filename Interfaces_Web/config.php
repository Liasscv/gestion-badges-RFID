<?php
// Paramètres de la base de données
$host = 'localhost';
$DB = 'acl';
$port = '3306';

// Définition du Data Source Name (DSN)
$dsn = 'mysql:host=' . $host . ';dbname=' . $DB . ';charset=utf8;port=' . $port;

// Définition des identifiants de connexion
$login = 'acl';
$mdp = 'acllp2i3!';

// Définition des options de PHP Data Objects (PDO)
$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Définir les constantes pour la connexion à la base de données
define('DB_DSN', $dsn);
define('DB_USER', $login);
define('DB_PASS', $mdp);
?>
