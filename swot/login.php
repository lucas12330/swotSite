<?php
// Inclure la classe de connexion à la base de données
include './PHP/bddConnect.php';

// Créer une instance de la classe Database
$db = new Database('localhost', 'swot', 'root', '');

if (isset($_POST['signin'])) {
    header("Location: signup.php"); // Redirection vers login.php après la déconnexion
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Se connecter à la base de données
        $pdo = $db->getConnection();

        // Préparer une requête pour vérifier les identifiants
        $stmt = $pdo->prepare("SELECT * FROM user WHERE MAIL = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Vérifier si l'utilisateur existe
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifier si le mot de passe est correct (assumons qu'il est haché)
            if (password_verify($password, $user['PASSWORD'])) {
                // Authentification réussie, démarrer la session
                session_start();
                $_SESSION['user_id'] = $user['id'];  // Enregistrer l'id de l'utilisateur dans la session
                $_SESSION['email'] = $user['email']; // Enregistrer l'email dans la session
                setcookie('userToken', $user['TOKEN'], time() + 86400, "/"); // Cookie valable 24h sur tout le site
                // Rediriger vers la page d'accueil

                header("Location: accueil.php");
                exit;
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun utilisateur trouvé avec cet email.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
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

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
