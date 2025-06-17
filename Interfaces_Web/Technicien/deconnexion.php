<?php
session_start();
session_destroy();
header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html");
exit();
?>
