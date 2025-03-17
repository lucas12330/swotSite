#include <Arduino.h>
int echo = 2;
int trigg = 3;

void setup() {
  pinMode(trigg, OUTPUT);
  pinMode(echo, INPUT);
  Serial.begin(9600);

}

void loop() {
  // put your main code here, to run repeatedly:

}

