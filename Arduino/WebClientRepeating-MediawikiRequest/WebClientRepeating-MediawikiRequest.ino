
/*
 * Ejemplo que viene en el IDE WebClientRepeating pero envía request a mediawiki
 */

#include <SPI.h>
#include <Ethernet.h>
#include <ArduinoJson.h>
#define NLEDS 8

/* ===========DECLARACIONES PARA LA CONEXIÓN ETHERNET=========== */

// assign a MAC address for the ethernet controller.
// fill in your address here:
byte mac[] = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED
};
// Set the static IP address to use if the DHCP fails to assign
IPAddress ip(192, 168, 0, 177);
IPAddress myDns(192, 168, 0, 1);

// initialize the library instance:
EthernetClient client;

//char server[] = "www.agroknowledge.org";  // also change the Host line in httpRequest()
IPAddress server(192, 168, 0, 8);

unsigned long lastConnectionTime = 0;           // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 10*1000;  // delay between updates, in milliseconds

/* ===========DECLARACIONES PARA USAR ARDUINOJSON=========== */
const size_t capacity = JSON_ARRAY_SIZE(10) + JSON_OBJECT_SIZE(2) + 40;
DynamicJsonDocument doc(capacity);
const char* latest = "00000000000000"; //Primera request.

void setup() {

  // start serial port:
  Serial.begin(9600);
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }

  // start the Ethernet connection:
  //Serial.println("Initialize Ethernet with DHCP:");
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
    // Check for Ethernet hardware present
    if (Ethernet.hardwareStatus() == EthernetNoHardware) {
      Serial.println("Ethernet shield was not found.  Sorry, can't run without hardware. :(");
      while (true) {
        delay(1); // do nothing, no point running without Ethernet hardware
      }
    }
    if (Ethernet.linkStatus() == LinkOFF) {
      Serial.println("Ethernet cable is not connected.");
    }
    // try to congifure using IP address instead of DHCP:
    Ethernet.begin(mac, ip, myDns);
    //Serial.print("My IP address: ");
    //Serial.println(Ethernet.localIP());
  } else {
    //Serial.print("  DHCP assigned IP ");
    //Serial.println(Ethernet.localIP());
  }
  // give the Ethernet shield a second to initialize:
  delay(1000);
}

void loop() {

  if (client.available()) {
        DeserializationError error = deserializeJson(doc, client);
        if (error) {
          Serial.print(F("deserializeJson() failed: "));
          Serial.println(error.c_str());
          return; //sale de loop().
        }else{
          JsonArray events = doc["events"];
          latest = doc["latest"];
          for (auto event : events) {
            switch (event.as<int>()) {
              case 0:
                event0();
                break;
              case 1:
                event1();
                break;
              default:
                //Nothing
                break;
            }
          }
          Serial.println(latest);
        }
  }

  // if ten seconds have passed since your last connection,
  // then connect again and send data:
  if (millis() - lastConnectionTime > postingInterval) {
    httpRequest(latest);
  }

}

// this method makes a HTTP connection to the server:
void httpRequest(const char* latest) {
  // close any connection before send a new request.
  // This will free the socket on the WiFi shield
  client.stop();

  // if there's a successful connection:
  if (client.connect(server, 80)) {
    //Serial.println("connecting...");
    // send the HTTP GET request:
    client.print("GET /mediawiki-1.31.5(actual)/api.php?action=lastevents&format=json&from=");
    client.println(latest);
    client.println("Host: www.arduino.cc");
    client.println("User-Agent: arduino-ethernet");
    client.println("Connection: close");
    client.println();

    // note the time that the connection was made:
    lastConnectionTime = millis();
  } else {
    // if you couldn't make a connection:
    Serial.println("connection failed");
  }
}

void event0(){
  Serial.println("Evento 0");
}

void event1(){
  Serial.println("Evento 1");
}
