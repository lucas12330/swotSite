#include <Arduino.h>
#define CAPTEUR_PIN A0  // Broche où est connecté le capteur IR

void setup() {
    Serial.begin(9600);  // Initialisation du moniteur série
}

void loop() {
    int valeurBrute = analogRead(CAPTEUR_PIN);  // Lecture du capteur
    float tension = valeurBrute * (5.0 / 1023.0);  // Conversion en tension (0-5V)
    
    // Approximation de la distance en cm basée sur la courbe du capteur
    float distance = 27.61 * pow(tension, -1.15);  

    Serial.print("Distance: ");
    Serial.print(distance);
    Serial.println(" cm");

    delay(500);  // Pause de 500ms avant la prochaine lecture
}
