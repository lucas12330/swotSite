<?php
// logout.php

session_start();  // Démarre la session
session_unset();  // Supprimer toutes les variables de session
session_destroy();  // Détruire la session

// Supprimer les cookies
setcookie('userToken', '', time() - 3600, '/');
setcookie('PHPSESSID', '', time() - 3600, '/'); // Supprimer le cookie de session
header("Location: ../login.php"); // Redirection vers login.php après la déconnexion
exit();
?>
