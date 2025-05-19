#include "sonar.hpp"

Sonar::Sonar(uint8_t trig, uint8_t echo) : trigPin(trig), echoPin(echo) {
    pinMode(trigPin, OUTPUT);
    pinMode(echoPin, INPUT);
}

float Sonar::readDistance() {
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);
    long duration = pulseIn(echoPin, HIGH, 30000); // Timeout 30ms
    float distance = duration * 0.0343 / 2;
    return distance;
}

String Sonar::readDistanceString() {
    float distance = readDistance();
    return String(distance, 2); // 2 décimales
}