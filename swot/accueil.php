<?php
//! Déconnexion
if (isset($_POST['logout'])) {
    setcookie('userToken', '', time() - 3600, '/');
    header("Location: login.php"); // Redirection vers login.php après la déconnexion
    exit();
}

$servname = 'localhost';
$dbname = 'swot';
$user = 'root';
$pass = '';
$userData = null;

//  Correction : on vérifie bien l'existence du cookie
if (isset($_COOKIE['userToken'])) {
    $token = $_COOKIE['userToken'];

    try {
        $dbco = new PDO("mysql:host=$servname;dbname=$dbname;charset=utf8", $user, $pass);
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbco->prepare("SELECT * FROM user WHERE TOKEN = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            // Token invalide → redirection vers la page de connexion (login.php)
            header("Location: login.php");
            exit();
        }

    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit();
    }
} else {
    // Cookie non défini → redirection vers la page de connexion (login.php)
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="./CSS/accueil.css">
    
</head>
<body>
    <header>
        <div class="deconnection">
            <form method="post">
                <button type="submit" name="logout">Déconnecter</button>
            </form>
        </div>
        <div>
            <h1 class="accueil">Bonjour : <?php echo htmlspecialchars($userData['PRENOM']) . ' ' . htmlspecialchars($userData['NOM']); ?></h1>
        </div>
    </header>

    <main>
        <p class="données-swot">DONNÉES SWOT</p>

        <div class="btn-container">
            <button class="btn">Prendre une mesure</button>
        </div>

    

        <!-- Texte après l'image -->
        <p class="base-de-données">Base De Données :</p>

        <table>
    <thead>
        <tr>
            <th>Date</th>            <!-- Colonne de gauche (Date) -->
            <th>Heure</th>           <!-- Colonne de droite (Heure) -->
            <th>Profondeur (en m)</th>  <!-- Colonne de profondeur -->
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>2025-04-07</td>   <!-- Exemple de date -->
            <td>10:00</td>        <!-- Exemple d'heure -->
            <td>10</td>           <!-- Exemple de profondeur -->
        </tr>
        <tr>
            <td>2025-04-07</td>
            <td>12:00</td>
            <td>20</td>
        </tr>
        <tr>
            <td>2025-04-07</td>
            <td>14:00</td>
            <td>30</td>
        </tr>
        <tr>
            <td>2025-04-08</td>
            <td>09:00</td>
            <td>15</td>
        </tr>
        <tr>
            <td>2025-04-08</td>
            <td>11:00</td>
            <td>25</td>
        </tr>
        <tr>
            <td>2025-04-08</td>
            <td>13:00</td>
            <td>35</td>
        </tr>
    </tbody>
</table>


    </main>
</body>
</html>

