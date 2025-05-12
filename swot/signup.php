<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./CSS/log.css">
</head>
<body>
    <header>
        <form method="post">
            <button type="submit" name="loginButton">Se connecter</button>
        </form>
    </header>
    <div class="container">
        <h2>Inscription</h2>
        <form  method="POST">
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

            <button type="submit" name="sub">S'inscrire</button>
        </form>
    </div>
</body>
</html>
<?php
// ! Fonction pour générer un token unique
function generateToken($lenght = 10){
    return substr(bin2hex(random_bytes($lenght)), 0, $lenght);
}

include 'PHP/bddConnect.php';
if (isset($_POST['loginButton'])) {
    header("Location: login.php");
    exit;
}

$db = new Database('localhost', 'swot', 'root', '');

// Envoie des données à la bdd
if (isset($_POST['sub'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $stayConnect = isset($_POST['stayConnect']) ? true : false; // Vérifier si la case est cochée

    // Générer un token unique
    $userToken = generateToken(20);

    try {
        // Connexion à la base de données
        $dbco = $db->getConnection();

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

        if ($stayConnect) {
            // Si "Rester connecté" est activé, définir un cookie avec une durée de 30 jours
            setcookie('userToken', $userToken, time() + 3600 * 24 * 30, '/'); // Cookie valable 30 jours
        } else {
            // Sinon, stocker le token dans la session
            session_start();
            $_SESSION['userToken'] = $userToken;
        }

        // Redirection vers la page d'accueil
        header("Location: accueil.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>