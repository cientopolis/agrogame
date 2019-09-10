#include <ArduinoJson.h>

/*
 * JSON CON DOS PÁGINAS EN STACK ANDANDO
 */

void setup() {

  Serial.begin(9600);

}

void loop() {

  char json[] = "{\"n\": 2,\"0\": {\"id\": \"1\",\"last_answer\": \"3\",\"avg_stars\": \"3.5000\"},\"1\": {\"id\": \"11\",\"last_answer\": \"5\",\"avg_stars\": \"3.0000\"}}";
  

  /*Arduino con tres páginas, ya no funciona.
  char json[] = "{\"n\": 3,\"0\": {\"id\": \"1\",\"last_answer\": \"3\",\"avg_stars\": \"3.5000\"},\"1\": {\"id\": \"11\",\"last_answer\": \"5\",\"avg_stars\": \"3.0000\"},\"2\": {\"id\": \"12\",\"last_answer\": \"4\",\"avg_stars\": \"2.5000\"}}";
  */
  
  DynamicJsonDocument doc(1024);
  deserializeJson(doc, json);
  int cantPages = doc["n"];

  for(int i=0;i<cantPages; i++){
  int idPage = doc[(String) i]["id"];
  int lastAnswer = doc[(String) i]["last_answer"];
  double avgStars = doc[(String) i]["avg_stars"];

  Serial.print("Cantidad de páginas: ");
  Serial.println(cantPages);
  Serial.print("Id: ");
  Serial.println(idPage);
  Serial.print("Última calificación: ");
  Serial.println(lastAnswer);
  Serial.print("Promedio de calificaciones: ");
  Serial.println(avgStars);  
  Serial.println(" ");  
  }
  Serial.println("============================");  
  delay(3000);
}
