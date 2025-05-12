<?php
require('vendor/autoload.php');
use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

// Configuration du client MQTT
$server   = 'broker.emqx.io';
$port     = 1883;
$clientId = rand(5, 15);
$username = 'emqx_user';
$password = 'public';
$clean_session = false;
$mqtt_version = MqttClient::MQTT_3_1_1;

// Configuration de la connexion
$connectionSettings = (new ConnectionSettings)
    ->setUsername($username)
    ->setPassword($password)
    ->setKeepAliveInterval(60)
    ->setLastWillTopic('emqx/test/last-will')
    ->setLastWillMessage('client disconnect')
    ->setLastWillQualityOfService(1);

$mqtt = new MqttClient($server, $port, $clientId, $mqtt_version);

try {
    $mqtt->connect($connectionSettings, $clean_session);
    echo "Client MQTT connecté avec succès.\n";

    // Abonnement au topic 'emqx/test'
    $mqtt->subscribe('test/swot', function ($topic, $message) {
        file_put_contents('messages.log', "Message reçu sur le topic [$topic] : $message\n", FILE_APPEND);
    }, 0);

    // Boucle pour écouter les messages
    while (true) {
        $mqtt->loop();
    }

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
} finally {
    $mqtt->disconnect();
    echo "Client MQTT déconnecté.\n";
}
?>
