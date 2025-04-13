<?php
include './PHP/bddConnect.php';
session_start();
$db = new Database('localhost', 'swot', 'root', '');

// Vérification du cookie ou de la session
$userData = null;
if (isset($_COOKIE['userToken'])) {
    $token = $_COOKIE['userToken'];
} elseif (isset($_SESSION['userToken'])) {
    $token = $_SESSION['userToken'];
} else {
    header("Location: login.php");
    exit();
}

try {
    $dbco = $db->getConnection();
    $stmt = $dbco->prepare("SELECT * FROM user WHERE TOKEN = :token");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        // Token invalide → redirection vers la page de connexion
        header("Location: login.php");
        exit();
    }

    // Récupération des données supplémentaires (exemple des mesures)
    $dataQuery = $dbco->prepare("SELECT DATE, HEURE, PROFONDEUR FROM data WHERE DATE != '0000-00-00' ORDER BY DATE ASC, HEURE ASC");
    $dataQuery->execute();
    $data = $dataQuery->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}
if(isset($_POST['btn'])) {
    // Redirection vers la page excel.php pour prendre une mesure
    header("Location: excel.php");
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
<script>
    window.addEventListener('beforeunload', function (e) {
        // Envoi d'une requête AJAX pour supprimer la session à la fermeture de la page
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'logout.php', true);
        xhr.send();
    });
</script>
    <header>
        <div class="deconnection">
            <form method="post" action="./PHP/logout.php">
                <button type="submit" name="logout" class="btn">Déconnecter</button>
            </form>
        </div>
        <div>
            <h1 class="accueil">Bonjour : <?php echo htmlspecialchars($userData['PRENOM']) . ' ' . htmlspecialchars($userData['NOM']); ?></h1>
        </div>
    </header>

    <main>
        <p class="données-swot">DONNÉES SWOT</p>

        <div class="btn-container">
            <form method="post">
                <button type="submit" class="btn" name="btn">Prendre une mesure</button>
            </form>
        </div>
        
        <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Profondeur (en m)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $dataRow) : ?>
            <tr>
                <td><?php echo htmlspecialchars($dataRow['DATE'] !== '0000-00-00' ? $dataRow['DATE'] : 'Date non définie'); ?></td>
                <td><?php echo htmlspecialchars(substr($dataRow['HEURE'], 0, 5)); ?></td>
                <td><?php echo htmlspecialchars($dataRow['PROFONDEUR']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>

        <div>
            <form method="post" action="./PHP/export_csv.php">
                <button type="submit" name="download" class="btn">Télécharger toutes les données</button>
            </form>
        </div>

    </main>
</body>
</html>
