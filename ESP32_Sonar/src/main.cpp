#include "BluetoothSerial.h"

BluetoothSerial SerialBT;

void setup() {
  Serial.begin(115200);
  SerialBT.begin("ESP32_BT", true);  // true = SPP esclave
  SerialBT.setPin("1234");           // Important !
  Serial.println("Bluetooth actif avec nom ESP32_BT et PIN 1234");
}

void loop() {
  if (SerialBT.available()) {
    char c = SerialBT.read();
    Serial.write(c);
    SerialBT.write(c);
  }
}
