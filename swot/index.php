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
    return
    substr(bin2hex(random_bytes($lenght)), 0, $lenght);
}

include 'PHP/bddConnect.php';
if (isset($_POST['loginButton'])) {
    header("Location: login.php");
    exit;
}


$db = new Database('localhost', 'swot', 'root', '');

// Envoie des donné à la bdd
if (isset($_POST['sub'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $stayConnect = isset($_POST['stayConnect']) ? $_POST['stayConnect'] : 0;

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
        setcookie('userToken', $userToken, time() + 3600 * 24 * 30, '/'); // Cookie valable 30 jours

        // Redirection vers la page d'accueil
        header("Location: accueil.php?token=$userToken");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Vérifier si l'utilisateur était déjà connecté
if (isset($_COOKIE['userToken'])) {
    try {
        // Connexion à la base de données

        $dbco = $db->getConnection();
        // Requête préparée sécurisée pour vérifier le token
        $stmt = $dbco->prepare("SELECT * FROM user WHERE TOKEN = :token");
        $stmt->execute([':token' => $_COOKIE['userToken']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // L'utilisateur est déjà connecté, rediriger vers la page d'accueil ou tableau de bord
            header("Location: accueil.php?token=" . $_COOKIE['userToken']);
            exit;
        } else {
            echo "Aucun utilisateur trouvé avec ce token.";
        }
    } catch (PDOException $e) {
        // En cas d'erreur de connexion à la base de données
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        exit;
    }
}
?>

