<?php
// Lancer le script MQTT en arrière-plan
exec("php -f test_mqtt.php > /dev/null 2>&1 &");
echo "Le script MQTT a été lancé en arrière-plan.";
?>
