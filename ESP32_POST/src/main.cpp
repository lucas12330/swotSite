#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>

//? Déclaration dees variable 
int ledPin = 2; // Pin de la LED
int led2 = 18; // Pin de la LED 2

const char* ssid = "WebSwotServer";
const char* password = "mx230448mx";

//? Déclaration du serveur du Broker MQTT
const char* mqtt_server = "broker.emqx.io";

WiFiClient espClient; // gère la connexion réseau
PubSubClient client(espClient); // client MQTT qui utilise espClient pour communiquer

JsonDocument doc;

//? Fonction de rappel pour traiter les messages MQTT
void callback(char* topic, byte* payload, unsigned int length);

void reconnect() {
  while (!client.connected()) {
    if (client.connect("ESP32Client")) {
      client.subscribe("test/swot");
    } else {
      delay(5000);
    }
  }
}

void setup(){
    Serial.begin(115200);
    delay(1000);

    WiFi.mode(WIFI_STA); //Optional
    WiFi.begin(ssid, password);
    Serial.println("\nConnecting");

    while(WiFi.status() != WL_CONNECTED){
        Serial.print(".");
        delay(100);
    }

    Serial.println("\nConnected to the WiFi network");
    Serial.print("Local ESP32 IP: ");
    Serial.println(WiFi.localIP());

    client.setServer(mqtt_server, 1883);
    client.setCallback(callback);
}

//? Callback quand un message est reçu
void callback(char* topic, byte* payload, unsigned int length) {
  String message;
  for (int i = 0; i < length; i++) message += (char)payload[i];

  if (String(topic) == "test/swot") {
    StaticJsonDocument<200> doc;
    DeserializationError error = deserializeJson(doc, message);
    if (!error) {
      for (JsonPair kv : doc.as<JsonObject>()) {
        String ordre = kv.key().c_str();      // La clé (ex: "allumer_led")
        String param = kv.value().as<String>(); // La valeur associée (ex: "rouge")
        // Ici tu peux faire un switch ou if sur "ordre" pour exécuter l'action correspondante
        if (ordre == "allumer_led") {
          // Allume la LED de la couleur "param"
            if (param == "rouge") {
                digitalWrite(ledPin, HIGH); // Allume la LED rouge
            } else if (param == "vert") {
                digitalWrite(led2, HIGH); // Allume la LED verte
            }
        } else if (ordre == "eteindre_led") {
          // Éteins la LED
            if (param == "rouge") {
                digitalWrite(ledPin, LOW); // Éteint la LED rouge
            } else if (param == "vert") {
                digitalWrite(led2, LOW); // Éteint la LED verte
            }
        }
        // etc.
      }
    }
  } else if (String(topic) == "test/swot") {
    if (message == "ON") {
      // Allume une LED par exemple
    }
  }
}

void loop(){
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
}