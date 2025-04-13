<?php
include './PHP/bddConnect.php';
$db = new Database('localhost', 'swot', 'root', '');

if (isset($_POST['signin'])) {
    header("Location: signup.php"); // Redirection vers signup.php
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stayConnect = isset($_POST['stayConnect']) ? true : false;

    try {
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE MAIL = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['PASSWORD'])) {
            // Authentification réussie
            if ($stayConnect) {
                // Enregistrer le token dans un cookie
                setcookie('userToken', $user['TOKEN'], time() + 86400 * 30, "/"); // Cookie valable 30 jours
            } else {
                // Enregistrer le token dans la session
                session_start();
                $_SESSION['userToken'] = $user['TOKEN'];
            }

            header("Location: accueil.php");
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="./CSS/log.css">
</head>
<body>
    <header>
        <form  method="post">
            <button type="submit" name="signin">S'inscrire</button>
        </form>
    </header>
    <div class="login-container">
        <h2>Se connecter</h2>
        <form method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="stayConnect">Rester connecter ?</label>
            <input type="checkbox" name="stayConnect" id="stayConnect" value="on">

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
