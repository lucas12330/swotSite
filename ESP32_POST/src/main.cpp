#include <WiFi.h>
#include <PubSubClient.h>
#include <Arduino.h>
#include <HTTPClient.h>
#include <../lib/sonar.hpp>

// WiFi
const char *ssid = "WebSwotServer"; // Enter your Wi-Fi name
const char *password = "mx230448mx";  // Enter Wi-Fi password

//? Déclaration des variables
int led = 2; // GPIO 2

//? Déclaration de callback
void callback(char *topic, byte *payload, unsigned int length);

// MQTT Broker
const char *mqtt_broker = "broker.emqx.io";
const char *topic = "test/swot";
const char *mqtt_username = "emqx";
const char *mqtt_password = "public";
const int mqtt_port = 1883;

Sonar sonar(23, 22); // Trig pin 23, Echo pin 22
WiFiClient espClient;
PubSubClient client(espClient);
HTTPClient http;

void setup() {
    // Set software serial baud to 115200;
    Serial.begin(115200);

    // Set GPIO 2 as output
    pinMode(led, OUTPUT);
    // Connecting to a WiFi network
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.println("Connecting to WiFi..");
    }
    Serial.println("Connected to the Wi-Fi network");
    //connecting to a mqtt broker
    client.setServer(mqtt_broker, mqtt_port);
    client.setCallback(callback);
    while (!client.connected()) {
        String client_id = "esp32-client-";
        client_id += String(WiFi.macAddress());
        Serial.printf("The client %s connects to the public MQTT broker\n", client_id.c_str());
        if (client.connect(client_id.c_str(), mqtt_username, mqtt_password)) {
            Serial.println("Public EMQX MQTT broker connected");
        } else {
            Serial.print("failed with state ");
            Serial.print(client.state());
            delay(2000);
        }
    }
    // Publish and subscribe
    client.publish(topic, "Hi, I'm ESP32 ^^");
    client.subscribe(topic);
}

void callback(char *topic, byte *payload, unsigned int length) {
    Serial.print("Message arrived in topic: ");
    Serial.println(topic);
    Serial.print("Message:");
    String message;
    for (int i = 0; i < length; i++) {
        message += (char) payload[i];
    }
    if(message == "on") {
        Serial.println("LED ON");
        digitalWrite(led, HIGH);
    } else if (message == "off") {
        Serial.println("LED OFF");
        digitalWrite(led, LOW);
    } else if (message == "php") {
        // Exemple d'appel POST vers un script PHP
        String var1 = sonar.readDistanceString(); // Utilisation de la méthode pour obtenir la distance
        String var2 = "100";
        String postData = "sonar=" + var1 + "&lidar=" + var2;

        http.begin("http://10.245.245.12/swotSite/swot/esp32.php");
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        int httpResponseCode = http.POST(postData);
        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.print("Réponse du serveur PHP : ");
            Serial.println(response);
        } else {
            Serial.print("Erreur HTTP : ");
            Serial.println(httpResponseCode);
        }
        http.end();
    } else {
        Serial.println("Unknown command");
    }
    Serial.print(message);
    Serial.println();
    Serial.println("-----------------------");
}

void loop() {
    client.loop();
}
