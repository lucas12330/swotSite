<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>accueil</title>
    <link rel="stylesheet" href="./CSS/accueil.css">
</head>
<header>
    <div class="deconnection">
        <form  method="post">
            <button type="submit" name="logout">Déconnecter</button>
        </form>
    </div>
    <div>
        <h1 class="accueil">Bonjour : </h1>
    </div>
</header>
<body>
    <p class="données-swot">DONNÉES SWOT </p>
    <p class="base-de-données">Base De Données : </p>
    <button class="btn">Prendre Une Mesure !</button>

</body>
</html>
<?php

//! Traitement de la déconnexion
if (isset($_POST['logout'])) {
    // Supprimer le cookie de session
    setcookie('userToken', '', time() - 3600, '/'); // Le cookie est supprimé en le définissant dans le passé
    // Rediriger vers la page d'accueil ou de connexion
    header("Location: index.php");
    exit();
}
 
<?php
$servname = 'localhost';
$dbname = 'swot';
$user = 'root';
$pass = '';
$userData = null; // Variable qui contiendra les infos de l'utilisateur

// Vérifie si le token est présent dans les cookies
if (isset($_GET['userToken'])) {
    $token = $_COOKIE['userToken'];

    try {
        // Connexion à la base de données
        $dbco = new PDO("mysql:host=$servname;dbname=$dbname;charset=utf8", $user, $pass);
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer une requête pour trouver l'utilisateur via le token
        $stmt = $dbco->prepare("SELECT * FROM user WHERE TOKEN = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        // Récupérer les données de l'utilisateur dans un tableau associatif
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Optionnel : redirection si utilisateur non trouvé
        if (!$userData) {
            header("Location: index.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
} else {
    // Pas de token => redirection vers la page de connexion
    header("Location: index.php");
    exit;
}
?>