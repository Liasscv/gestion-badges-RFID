<?php
// Mot de passe à hacher
$password1 = "Agent";  // Le mot de passe fourni par l'utilisateur
$password2 = "Technicien";
// Hachage du mot de passe avec password_hash (par défaut, cela utilise l'algorithme BCRYPT)
$hashedPassword1 = password_hash($password1, PASSWORD_DEFAULT);
$hashedPassword2 = password_hash($password2, PASSWORD_DEFAULT);

// Affichage du mot de passe haché
echo "Mot de passe haché 1 : " . $hashedPassword1 . "<br><br>"; 
echo "Mot de passe haché 2 : " . $hashedPassword2;
?>
