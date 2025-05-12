<?php 
require('vendor/autoload.php');
use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

date_default_timezone_set('Europe/Paris'); // Définir le fuseau horaire

//! Mise en place de la connexion MQTT 
$server   = 'broker.emqx.io';
$port     = 1883;
$clientId = rand(5, 15);
$username = 'emqx_user';
$password = 'public';
$clean_session = false;
$mqtt_version = MqttClient::MQTT_3_1_1;

//! Fonction de connexion au serveur MQTT
$connectionSettings = (new ConnectionSettings)
  ->setUsername($username)
  ->setPassword($password)
  ->setKeepAliveInterval(60)
  ->setLastWillTopic('emqx/test/last-will')
  ->setLastWillMessage('client disconnect')
  ->setLastWillQualityOfService(1);

$mqtt = new MqttClient($server, $port, $clientId, $mqtt_version);
$mqtt->connect($connectionSettings, $clean_session);

if (isset($_POST['prog'])) {
    // Récupérer la date et l'heure saisies par l'utilisateur
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Combiner la date et l'heure en un seul timestamp
    $userTimestamp = strtotime("$date $time");
    $currentTimestamp = time();

    // Calculer la durée en secondes
    $duration = $userTimestamp - $currentTimestamp;

    if ($duration > 0) {
        //! La fonction de publication 
        $payload = array(
            'protocol' => 'tcp',
            'date' => date('Y-m-d H:i:s'),
            'ordre' => 'mesureIM',
            'duration' => $duration // Ajouter la durée en secondes
        );
        $mqtt->publish(
            // topic
            'prog/swot',
            // payload
            json_encode($payload),
            // qos
            0,
            // retain
            true
        );
        printf("Message envoyé au topic MQTT avec une durée de %d secondes.\n", $duration);

        // Déconnexion du client MQTT après l'envoi des messages
        $mqtt->disconnect();
        echo "<script>alert('Voulez-vous vraiment quitter ?');</script>";
        header("Location: accueil.php");
        exit();
    } else {
        echo "La date et l'heure saisies doivent être dans le futur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmer une mesure</title>
    <link rel="stylesheet" href="CSS/prog_mesure.css">
</head>
<body>
    <form method="post">
        <label for="time">Entrer l'heure de la mesure :</label>
        <input type="time" name="time" id="time" required>
        <label for="date">Entrer la date de la mesure :</label>
        <input type="date" name="date" id="date" required>
        <button type="submit" name="prog" class="btn">Tester</button>
    </form>
</body>
</html>