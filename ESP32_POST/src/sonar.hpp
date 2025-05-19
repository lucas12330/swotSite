#ifndef SONAR_HPP
#define SONAR_HPP
#include <Arduino.h>

class Sonar {
public:
    Sonar(uint8_t trig, uint8_t echo);
    float readDistance();
    String readDistanceString();
private:
    uint8_t trigPin;
    uint8_t echoPin;
};

#endif // SONAR_HPP