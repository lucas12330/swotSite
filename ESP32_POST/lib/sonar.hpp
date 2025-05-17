#ifndef SONAR_HPP
#define SONAR_HPP

#include <Arduino.h>

class Sonar {
private:
    uint8_t trigPin;
    uint8_t echoPin;
public:
    Sonar(uint8_t trig, uint8_t echo);
    float readDistance();
    String readDistanceString(); // Ajout de la méthode pour retourner la distance en string
};

#endif // SONAR_HPP