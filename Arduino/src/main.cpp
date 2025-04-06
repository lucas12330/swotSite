<<<<<<< Updated upstream
// Sharp IR GP2Y0A41SK0F Distance Test
// http://tinkcore.com/sharp-ir-gp2y0a41-skf/
#include <Arduino.h>
#define sensor A3 // Sharp IR GP2Y0A41SK0F (4-30cm, analog)

void setup() {
  Serial.begin(9600); // start the serial port
}

void loop() {
  
  // 5v
  float volts = analogRead(sensor)*0.0048828125;  // value from sensor * (5/1024)
  int distance = 13*pow(volts, -1); // worked out from datasheet graph
  delay(1000); // slow down serial port 
  
  if (distance <= 30){
    Serial.println(distance);   // print the distance
  }
}
=======
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

>>>>>>> Stashed changes
