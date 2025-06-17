<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    try {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $pdo_options);
        
        $sql = $pdo->prepare("SELECT id_utilisateur_web, login, mot_de_passe, role FROM utilisateur_web WHERE login = ?");
        $sql->execute([$email]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if (password_verify($password, $user['mot_de_passe'])) {
            
                $_SESSION['user_id'] = $user['id_utilisateur_web'];
                $_SESSION['user_email'] = $user['login'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($user['role'] == 'Agent') {
                    header("Location: Agent/espace_agent.php");
                } elseif ($user['role'] == 'Technicien') {
                    header("Location: Technicien/espace_technicien.php");
                }
                exit();
            } else {
                header("Location: Index.html?error=motdepasse");
                exit();
            }
        } else {
            header("Location: Index.html?error=utilisateur");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: Index.html?error=serveur");
        exit();
    }
} else {
    header("Location: Index.html");
    exit();
}
?>