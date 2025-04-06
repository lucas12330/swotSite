<?php
/*
 * Created on Sun Apr 06 2025
 *
 * Copyright (c) 2025 Altear Technologies
 */

$servname = 'localhost';
$dbname = 'swot';
$user = 'root';
$pass = '';

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];
$stayConnect = isset($_POST['stayConnect']) ? $_POST['stayConnect'] : 0;

try {
    // Connexion à la base de données
    $dbco = new PDO("mysql:host=$servname;dbname=$dbname;charset=utf8", $user, $pass);
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifier si un cookie de token existe
    if (isset($_COOKIE['userToken'])) {
        $userToken = $_COOKIE['userToken'];
    } else {
        // Générer un token unique
        $userToken = generateToken(20);
    }
    // Requête préparée sécurisée
    $dataExecuter = "INSERT INTO user (NOM, PRENOM, MAIL, ROLE, PASSWORD, TOKEN) VALUES (:nom, :prenom, :email, :role, :password, :token)";
    $stmt = $dbco->prepare($dataExecuter);
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':role' => $role,
        ':token' => $userToken,
        ':password' => password_hash($password, PASSWORD_DEFAULT) // Sécurisation du mot de passe
    ]);
    setcookie('userToken', $userToken, time() + 3600 * 24 * 30, '/'); // Cookie valable 30 jours




    
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

function generateToken($lenght = 10){
    return
    substr(bin2hex(random_bytes($lenght)), 0, $lenght);
}
?>
