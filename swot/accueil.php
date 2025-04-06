<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>accueil</title>
    <link rel="stylesheet" href="./CSS/accueil.css">
</head>
<body>
    <p class="données-swot">DONNÉES SWOT </p>
    <p class="base-de-données">Base De Données : </p>
    <button class="btn">Prendre Une Mesure !</button>

</body>
</html>
<?php
// Vérifier si la variable 'token' est passée dans l'URL
if (isset($_GET['token'])) {
    $userToken = $_GET['token'];
    echo "Le token utilisateur est : " . $userToken;
} else {
    echo "Aucun token trouvé.";
}
?>
