#include <Arduino.h>

// Déclaration des pin
const int trig = 25;
const int echo = 33;

void setup() {
    Serial.begin(9600);  // Initialisation du moniteur série
    pinMode(trig, OUTPUT);  // Définir la broche trig comme sortie
    pinMode(echo, INPUT);  // Définir la broche echo comme entrée
}

void loop() {
    digitalWrite(trig, LOW);  // Assurez-vous que le trig est à LOW
    delayMicroseconds(2);  // Attendre 2 microsecondes
    digitalWrite(trig, HIGH);  // Envoyer un signal HIGH sur le trig
    delayMicroseconds(10);  // Attendre 10 microsecondes
    digitalWrite(trig, LOW);  // Passer à LOW
    int duration = pulseIn(echo, HIGH);  // Lire la durée du signal HIGH sur le echo
    float distance = (duration * 0.03432) / 2;  // Calculer la distance en cm
    Serial.print("Distance: ");  // Afficher la distance
    Serial.print(distance);  // Afficher la distance calculée
    Serial.println(" cm");  // Afficher l'unité de mesure


    delay(500);  // Pause de 500ms avant la prochaine lecture
}
