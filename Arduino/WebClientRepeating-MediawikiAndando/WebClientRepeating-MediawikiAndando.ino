/*
  Esta versión funciona con mediawiki. Mantiene un NLEDS de la cantidad de paginas que se pueden mapear a un led.
  Hay un arreglo que representan las últimas calificaciones de cada página. Si no tuvo -1.
*/

/*
  Repeating Web client

 This sketch connects to a a web server and makes a request
 using a Wiznet Ethernet shield. You can use the Arduino Ethernet shield, or
 the Adafruit Ethernet shield, either one will work, as long as it's got
 a Wiznet Ethernet module on board.

 This example uses DNS, by assigning the Ethernet client with a MAC address,
 IP address, and DNS address.

 Circuit:
 * Ethernet shield attached to pins 10, 11, 12, 13

 created 19 Apr 2012
 by Tom Igoe
 modified 21 Jan 2014
 by Federico Vanzati

 http://www.arduino.cc/en/Tutorial/WebClientRepeating
 This code is in the public domain.

 */

#include <SPI.h>
#include <Ethernet.h>
#include <ArduinoJson.h>
#define NLEDS 8

#include <FastLED.h>
#define BRIGTHNESS 20//0..255
#define DATA_PIN 6
#define CLOCK_PIN 13
CRGB leds[NLEDS];

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

//char server[] = "www.arduino.cc";  // also change the Host line in httpRequest()
IPAddress server(192, 168, 0, 8);

//SETUP PARA EL JSON
  const size_t capacity = 5*JSON_OBJECT_SIZE(3) + JSON_OBJECT_SIZE(6) + 220;
  DynamicJsonDocument doc(capacity);

unsigned long lastConnectionTime = 0;           // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 10*1000;  // delay between updates, in milliseconds

/*Setup para el manejo de páginas. Posibilidad hasta 50 paginas.*/
int lastcalif[NLEDS];

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

  //Setup paginas
  for(int i=0; i<NLEDS; i++){
    lastcalif[i] = -1;
  }

  //Setup leds
  FastLED.addLeds<NEOPIXEL, DATA_PIN>(leds, NLEDS);
  FastLED.setBrightness(BRIGTHNESS);
}

void loop() {
  
  // if there's incoming data from the net connection.
  // send it out the serial port.  This is for debugging
  // purposes only:
  if (client.available()) {
    
    deserializeJson(doc, client);

    for(int i=0; i<NLEDS; i++){
      leds[i] = CRGB::Black;
    }
    FastLED.show();

    /*Deserealiza por cada página*/
    int n = doc["n"];

    Serial.println(n);
    
    //Mapea las páginas a los leds
    for(int i=0; i<n; i++){
     leds[i] = CRGB::Blue;
    }
    FastLED.show();
    
    for(int i=0; i<n; i++){
      JsonObject page = doc[(String)i];
      const char* page_id = page["id"];
      const char* page_last_answer = page["last_answer"];
      const char* page_avg_stars = page["avg_stars"];
      /*Si la página no existía festeja*/
      if(lastcalif[i] != atoi(page_last_answer)){
        celebrate(i, atoi(page_last_answer));
        lastcalif[i] = atoi(page_last_answer);
      }
    }
    
  }

  // if ten seconds have passed since your last connection,
  // then connect again and send data:
  if (millis() - lastConnectionTime > postingInterval) {
    httpRequest();
  }

}

// this method makes a HTTP connection to the server:
void httpRequest() {
  // close any connection before send a new request.
  // This will free the socket on the WiFi shield
  client.stop();

  // if there's a successful connection:
  if (client.connect(server, 80)) {
    Serial.println("connecting...");
    // send the HTTP GET request:
    client.println("GET /mediawiki-1.33.0/api.php?action=agroknowledge&format=json");
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

void celebrate(int nled, int vote){
  CRGB tmp = leds[nled];
  for(int i=0; i<vote; i++){
    leds[nled] = CRGB::Green;
    FastLED.show();
    delay(500);
    leds[nled] = CRGB::Black;
    FastLED.show();
    delay(500);  
  }
  leds[nled] = tmp;
  FastLED.show();
  
}

/*void printCalif(){
  for(int i=0; i<NLEDS;i++){
    Serial.print(" ");
    Serial.print(lastcalif[i]);
  }
  Serial.println(".");
}*/
