
/*
   Ejemplo que viene en el IDE WebClientRepeating pero envía request a mediawiki
*/

#include <SPI.h>
#include <Ethernet.h>
#include <ArduinoJson.h>
#include "FastLED.h"


/*===========DECLARACIONES PARA LEDS===========*/
#define NUM_LEDS 8
CRGB leds[NUM_LEDS];
#define PIN 7

/* ===========DECLARACIONES PARA LA CONEXIÓN ETHERNET=========== */

// assign a MAC address for the ethernet controller.
// fill in your address here:
byte mac[] = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED
};
// Set the static IP address to use if the DHCP fails to assign
IPAddress ip(192, 168, 10, 55); //lifia
IPAddress myDns(192, 168, 0, 1);

// initialize the library instance:
EthernetClient client;

//char server[] = "www.agroknowledge.org";  // also change the Host line in httpRequest()
//IPAddress server(192, 168, 10, 100); //LIFIA
IPAddress server(192, 168, 0, 8); //MI CASA
String mediawikiGet = "/mediawiki-1.31.5(actual)/api.php?action=lastevents&format=json&from=";

unsigned long lastConnectionTime = 0;           // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 10 * 1000; // delay between updates, in milliseconds

/* ===========DECLARACIONES PARA USAR ARDUINOJSON=========== */
const size_t capacity = JSON_ARRAY_SIZE(10) + JSON_OBJECT_SIZE(3) + 40;
DynamicJsonDocument doc(capacity);
const char* latest = "00000000000000"; //Primera request.
int state = 0;

void setup() {

  //CONFIGURACIONES DE ADAFRUIT
  FastLED.addLeds<WS2811, PIN, GRB>(leds, NUM_LEDS).setCorrection( TypicalLEDStrip );

  //CONFIGURACIÓN DE LEDS DE SALIDA
  pinMode(7,OUTPUT);
  digitalWrite(7,LOW);

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

  Serial.print("Mostrando estado: ");
  Serial.println(state);
  FadeInOut(0xff, 0xff, 0xff); // white 
  Serial.println("listo!");

  if (client.available()) {
    DeserializationError error = deserializeJson(doc, client);
    if (error) {
      Serial.print(F("deserializeJson() failed: "));
      Serial.println(error.c_str());
      return; //sale de loop().
    } else {
      JsonArray events = doc["events"];
      latest = doc["latest"];
      state = doc["state"];
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
    Serial.println("Request...");
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
    client.print("GET ");
    client.print(mediawikiGet);
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

void event0() {
  Serial.println("Evento login!");
  CylonBounce(0, 0xff, 0, 2, 50, 25);
}

void event1() {
  /*Serial.println("Evento pagina guardada!");
  colorWipe(strip.Color(55,15,0), 50); //VERDE
  delay(500);
  colorWipe(strip.Color(0, 0, 0), 25);*/
}

//FUNCIONES FASTLED
void FadeInOut(byte red, byte green, byte blue){
  float r, g, b;
      
  for(int k = 0; k < 256; k=k+1) { 
    r = (k/256.0)*red;
    g = (k/256.0)*green;
    b = (k/256.0)*blue;
    setAll(r,g,b);
    showStrip();
  }
     
  for(int k = 255; k >= 0; k=k-2) {
    r = (k/256.0)*red;
    g = (k/256.0)*green;
    b = (k/256.0)*blue;
    setAll(r,g,b);
    showStrip();
  }
}

// *** REPLACE TO HERE ***

void showStrip() {
 #ifdef ADAFRUIT_NEOPIXEL_H 
   // NeoPixel
   strip.show();
 #endif
 #ifndef ADAFRUIT_NEOPIXEL_H
   // FastLED
   FastLED.show();
 #endif
}

void setPixel(int Pixel, byte red, byte green, byte blue) {
 #ifdef ADAFRUIT_NEOPIXEL_H 
   // NeoPixel
   strip.setPixelColor(Pixel, strip.Color(red, green, blue));
 #endif
 #ifndef ADAFRUIT_NEOPIXEL_H 
   // FastLED
   leds[Pixel].r = red;
   leds[Pixel].g = green;
   leds[Pixel].b = blue;
 #endif
}

void setAll(byte red, byte green, byte blue) {
  for(int i = 0; i < NUM_LEDS; i++ ) {
    setPixel(i, red, green, blue); 
  }
  showStrip();
}

//Viborita

void CylonBounce(byte red, byte green, byte blue, int EyeSize, int SpeedDelay, int ReturnDelay){

  for(int i = 0; i < NUM_LEDS-EyeSize-2; i++) {
    setAll(0,0,0);
    setPixel(i, red/10, green/10, blue/10);
    for(int j = 1; j <= EyeSize; j++) {
      setPixel(i+j, red, green, blue); 
    }
    setPixel(i+EyeSize+1, red/10, green/10, blue/10);
    showStrip();
    delay(SpeedDelay);
  }

  delay(ReturnDelay);

  for(int i = NUM_LEDS-EyeSize-2; i > 0; i--) {
    setAll(0,0,0);
    setPixel(i, red/10, green/10, blue/10);
    for(int j = 1; j <= EyeSize; j++) {
      setPixel(i+j, red, green, blue); 
    }
    setPixel(i+EyeSize+1, red/10, green/10, blue/10);
    showStrip();
    delay(SpeedDelay);
  }
  
  delay(ReturnDelay);
}
