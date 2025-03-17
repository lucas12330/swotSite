#include <Arduino.h>
#include <SoftwareSerial.h>

// Initialisation de la liaison série LoRa
SoftwareSerial loraSerial(2, 3);  // RX, TX

void setup() {
  // Initialisation du moniteur série
  loraSerial.begin(9600);
  Serial.begin(9600);

}

void loop() {
  // put your main code here, to run repeatedly:
  if(loraSerial.available()) {
    String data = loraSerial.readString();
    Serial.println(loraSerial.read());
  }
  if(Serial.available()) {
    String data = Serial.readString();
    loraSerial.println(data);
  }

}

