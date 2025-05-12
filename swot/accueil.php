<?php
include './PHP/bddConnect.php';
require('vendor/autoload.php');
use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

//! Mise en place de la connexiion MQTT 
$server   = 'broker.emqx.io';
$port     = 1883;
$clientId = rand(5, 15);
$username = 'emqx_user';
$password = 'public';
$clean_session = false;
$mqtt_version = MqttClient::MQTT_3_1_1;

//! Fonction de connecion au serveur MQTT
$connectionSettings = (new ConnectionSettings)
  ->setUsername($username)
  ->setPassword($password)
  ->setKeepAliveInterval(60)
  ->setLastWillTopic('emqx/test/last-will')
  ->setLastWillMessage('client disconnect')
  ->setLastWillQualityOfService(1);


$mqtt = new MqttClient($server, $port, $clientId, $mqtt_version);

$mqtt->connect($connectionSettings, $clean_session);
printf("client connected\n");

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

    //! La fonction de publication 
    $payload = array(
        'protocol' => 'tcp',
        'date' => date('Y-m-d H:i:s'),
        'ordre' => 'mesureIM'
    );
    $mqtt->publish(
        // topic
        'test/swot',
        // payload
        "bonjour",
        // qos
        0,
        // retain
        true
    );
    printf("msg send\n");
    sleep(1);

    // Déconnexion du client MQTT après l'envoi des messages
    $mqtt->disconnect();
    header("Location: accueil.php");
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
            <form action='./prog_mesure.php' method="post">
                <button type="submit" class="btn">Programmer une mesure</button>
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

        <script>
            // Fonction pour rafraîchir les messages MQTT
            function refreshMessages() {
                fetch('messages.log')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('mqtt-messages').innerText = data;
                    })
                    .catch(error => console.error('Erreur lors du chargement des messages :', error));
            }

            // Rafraîchir les messages toutes les 5 secondes
            setInterval(refreshMessages, 5000);

            // Charger les messages immédiatement au chargement de la page
            refreshMessages();
        </script>

        <div>
            <h2>Messages MQTT reçus :</h2>
            <pre id="mqtt-messages">Chargement des messages...</pre>
        </div>

    </main>
</body>
</html>
