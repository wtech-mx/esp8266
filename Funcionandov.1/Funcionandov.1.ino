#include <ESP8266HTTPClient.h>
#include <ESP8266WiFiMulti.h>
#include <WiFiClient.h>
#include <TinyGPS++.h>
#include <SoftwareSerial.h>

// defino credenciales red
//const char* ssid ="INFINITUMFC96";
//const char* password ="TAr5tV9rTH";

const char* ssid ="INFINITUMD226";
const char* password ="xXTWR474G7";

// Variables para lectura del DHT 11
float t;
float h;
float f;
float hif;
float hic;

#include "DHT.h"

#define DHTPIN 14     // Digital pin connected to the DHT sensor
// Feather HUZZAH ESP8266 note: use pins 3, 4, 5, 12, 13 or 14 --
// Pin 15 can work but DHT must be disconnected during program upload.

// Uncomment whatever type you're using!
#define DHTTYPE DHT11   // DHT 11

DHT dht(DHTPIN, DHTTYPE);
WiFiClient client; 

// Configuración para el GPS
const int RXPin = 12; // Pin RX del GPS conectado a D6
const int TXPin = 13; // Pin TX del GPS conectado a D7
const uint32_t GPSBaud = 9600;

TinyGPSPlus gps;
SoftwareSerial ss(RXPin, TXPin);

void setup() {
  Serial.begin(115200);
  Serial.println(F("DHT 11 prueba de conexión con el servidor"));
  
  dht.begin();
  ss.begin(GPSBaud);

  WiFi.begin(ssid, password);
  Serial.print("Conectando...");
  while (WiFi.status()!= WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("Conexión OK!");
  Serial.print("IP Local: ");
  Serial.println(WiFi.localIP());

}


void loop() {
  LecturaTH();
  LecturaGPS();
  EnvioDatos();
}


// funcion de lectura de temperatura y humedad
void LecturaTH(){

  h = dht.readHumidity();
  t = dht.readTemperature();
  f = dht.readTemperature(true);

  // Check if any reads failed and exit early (to try again).
  if (isnan(h) || isnan(t) || isnan(f)) {
    Serial.println(F("Falla al leer el sensor DHT!"));
    return;
  }

  // Compute heat index in Fahrenheit (the default)
  hif = dht.computeHeatIndex(f, h);
  // Compute heat index in Celsius (isFahreheit = false)
  hic = dht.computeHeatIndex(t, h, false);

  Serial.print(F("Humidity: "));
  Serial.print(h);
 
  Serial.print(F("%  Temperature: "));
  Serial.print(t);
  Serial.print(F("°C "));
  Serial.print(f);
 
  Serial.print(F("°F  Heat index: "));
  Serial.print(hic);
  Serial.print(F("°C "));D
  Serial.print(hif);
  Serial.println(F("°F"));  
}

void LecturaGPS() {
  while (ss.available() > 0) {
    if (gps.encode(ss.read())) {
      if (gps.location.isUpdated()) {
        Serial.print(F("Latitud: "));
        Serial.print(gps.location.lat(), 6);
        Serial.print(F(" Longitud: "));
        Serial.println(gps.location.lng(), 6);
      }
    }
  }
}



// rutina de envio de datos por POST
void EnvioDatos(){
  
  if (WiFi.status() == WL_CONNECTED){ 

      HTTPClient http;
      String datos_a_enviar = "temperatura=" + String(t) + "&humedad=" + String(h);
      
      if (gps.location.isValid()) {
        datos_a_enviar += "&latitud=" + String(gps.location.lat(), 6) + "&longitud=" + String(gps.location.lng(), 6);
      } else {
        // Si no es válido, envía la ubicación predeterminada
        datos_a_enviar += "&latitud=19.385404&longitud=-99.226330";
      }

     http.begin(client,"http://wtech.com.mx/esp8266/EspPost.php");
     http.addHeader("Content-Type", "application/x-www-form-urlencoded"); // defino texto plano..

     int codigo_respuesta = http.POST(datos_a_enviar);

     if (codigo_respuesta>0){
      Serial.println("Código HTTP: "+ String(codigo_respuesta));
        if (codigo_respuesta == 200){ 
          String cuerpo_respuesta = http.getString();
          Serial.println("El servidor respondió: ");
          Serial.println(cuerpo_respuesta);
        }
     } else {
        Serial.print("Error enviado POST, código: ");
        Serial.println(codigo_respuesta);
     }

       http.end();  // libero recursos
       
  } else {
     Serial.println("Error en la conexion WIFI");
  }
  delay(60000); //espera 60s
}
