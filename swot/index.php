<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./CSS/index.css">
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form action="./PHP/signup.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>


            <label for="role">Rôle :</label>
            <select id="role" name="role">
                <option value="stagiaire">Stagiaire</option>
                <option value="responsable">Responsable</option>
            </select>

            <label for="stayConnect">Rester connecter ?</label>
            <input type="checkbox" name="stayConnect" id="stayConnect" value="on">

            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>
<?php
$servname = 'localhost';
$dbname = 'swot';
$user = 'root';
$pass = '';

// Vérifier si l'utilisateur était déjà connecté
if(isset($_COOKIE['userToken'])) {
    try {
        //TODO: il faut refaire le code pour utiliser la session php et transmettre le token dans la session
        // Connexion à la base de données
        $dbco = new PDO("mysql:host=$servname;dbname=$dbname;charset=utf8", $user, $pass);
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête préparée sécurisée pour vérifier le token
        $stmt = $dbco->prepare("SELECT * FROM user WHERE TOKEN = :token");
        $stmt->execute([':token' => $_COOKIE['userToken']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // L'utilisateur est déjà connecté, rediriger vers la page d'accueil ou tableau de bord
            header("Location: accueil.php?token=" . $userToken);
            exit;
        }
    } catch (PDOException $e) {
        // En cas d'erreur de connexion à la base de données
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        exit;
    }
}
?>
