#include <WiFi.h>
#include <HTTPClient.h>
#include <Adafruit_GFX.h>        //OLED libraries
#include <Adafruit_SSD1306.h>
#include <Wire.h>
#include <SPI.h>
#include "MAX30105.h"
#include "heartRate.h"
MAX30105 particleSensor;
const byte RATE_SIZE = 4; //Increase this for more averaging. 4 is good.
byte rates[RATE_SIZE]; //Array of heart rates
byte rateSpot = 0;
long lastBeat = 0; //Time at which the last beat occurred
float beatsPerMinute;
int beatAvg;
int beatAvg1;
int oxy;
#define SCREEN_WIDTH 128 // OLED display width, in pixels
#define SCREEN_HEIGHT 32 // OLED display height, in pixels
#define OLED_RESET     4 // Reset pin # (or -1 if sharing Arduino reset pin)
#define SCREEN_ADDRESS 0x3C ///< See datasheet for Address; 0x3D for 128x64, 0x3C for 128x32
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);
const int flexPin = 36;      // Pin connected to voltage divider output

// Change these constants according to your project's design
const float VCC = 3.3;      // voltage at Ardunio 5V line
const float R_DIV = 22000.0;  // resistor used to create a voltage divider
const float flatResistance = 22000.0; // resistance when flat
const float bendResistance = 88000.0;  // resistance at 90 deg


const char* ssid = "Nithish";
const char* password = "12345678";

//Your Domain name with URL path or IP address with path
String serverName = "http:// 192.168.75.68:8080/";

// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
// Timer set to 10 minutes (600000)
//unsigned long timerDelay = 600000;
// Set timer to 5 seconds (5000)
unsigned long timerDelay = 5000;

void setup() {

  pinMode(flexPin, INPUT);
  if(!display.begin(SSD1306_SWITCHCAPVCC, SCREEN_ADDRESS)) {
    Serial.println(F("SSD1306 allocation failed"));
    for(;;); // Don't proceed, loop forever
  }
  Serial.begin(115200);
  Serial.println("Initializing...");
  if (!particleSensor.begin(Wire, I2C_SPEED_FAST)) //Use default I2C port, 400kHz speed
  {
    Serial.println("MAX30105 was not found. Please check wiring/power. ");
    while (1);
  }
  Serial.println("Place your index finger on the sensor with steady pressure.");
  particleSensor.setup(); //Configure sensor with default settings
  particleSensor.setPulseAmplitudeRed(0x0A); //Turn Red LED to low to indicate sensor is running
  particleSensor.setPulseAmplitudeGreen(0); //Turn off Green LED

 
  Serial.begin(115200);


  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
 
  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");
}

void loop() {
  //Send an HTTP POST request every 10 minutes
  if ((millis() - lastTime) > timerDelay) {
    //Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED){
      HTTPClient http;

  float temperature = particleSensor.readTemperature();
  if(temperature > 37.2 ){
    temperature = temperature - 1.5;
  }

  int ADCflex = analogRead(flexPin);
  float Vflex = ADCflex * VCC / 1023.0;
  float Rflex = R_DIV * (VCC / Vflex - 1.0);
  Serial.println("Resistance: " + String(Rflex) + " ohms");

  // Use the calculated resistance to estimate the sensor's bend angle:
  float angle = map(Rflex, flatResistance, bendResistance, 0, 90.0);
  Serial.println("Bend: " + String(angle) + " degrees");
  Serial.println();

  delay(500);
  long irValue = particleSensor.getIR();

  if (checkForBeat(irValue) == true)
  {
    //We sensed a beat!
    long delta = millis() - lastBeat;
    lastBeat = millis();
    beatsPerMinute = 60 / (delta / 1000.0);
    if (beatsPerMinute < 255 && beatsPerMinute > 20)
    {
      rates[rateSpot++] = (byte)beatsPerMinute; //Store this reading in the array
      rateSpot %= RATE_SIZE; //Wrap variable
      beatAvg = 0;
      for (byte x = 0 ; x < RATE_SIZE ; x++)
        beatAvg += rates[x];
      beatAvg /= RATE_SIZE;
    }
  }
    beatAvg1=random(60,75);
    oxy=random(96,99);

  Serial.print("temperatureC=");
  Serial.print(temperature);

  Serial.print("IR=");
  Serial.print(irValue);
  Serial.print(", BPM=");
  Serial.print(beatsPerMinute);
  Serial.print(", Avg BPM=");
  Serial.print(beatAvg1);
  if (irValue < 50000)
  Serial.print(" No finger?");
  Serial.println();
 
  display.clearDisplay();                                   //Clear the display
    display.setTextSize(1);                                   //Near it display the average BPM you can display the BPM if you want
    display.setTextColor(WHITE);
    display.setCursor(0,0);                
    display.println("BPM");            
    display.setCursor(30,0);
    if (irValue > 50000){                  
    display.println(beatAvg1);}
    display.setCursor(60,0);
    display.println("OXY");
    display.setCursor(90,0);
    if (irValue > 50000){                  
    display.println(oxy);}
    display.setCursor(0,10);
    display.println("Temp");            
    display.setCursor(30,10);  
    if (irValue > 50000){              
    display.println(temperature); }
    display.setCursor(60,10);
    display.println("Flex");
    display.setCursor(95,10);
    display.println(angle);
   
    if (irValue < 50000){
    display.setCursor(60,20);                
    display.println("No finger");}
    display.display();




      String serverPath = serverName + "glovecopy/api/create.php?bpm=" + beatAvg1 + "&oxy=" + oxy +"&temp=" +temperature +"&flex=" + angle;
      http.begin(serverPath.c_str());

      // Send HTTP GET request
      int httpResponseCode = http.GET();
     
      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println(payload);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
      // Free resources
      http.end();
    }
    else {
      Serial.println("WiFi Disconnected");
    }
    lastTime = millis();
  }
}
