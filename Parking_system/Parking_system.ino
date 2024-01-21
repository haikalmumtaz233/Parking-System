#define BLYNK_TEMPLATE_ID "."
#define BLYNK_TEMPLATE_NAME "Parking"
#define BLYNK_AUTH_TOKEN "TOKEN"
#define BLYNK_PRINT Serial

#include <Servo.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <BlynkSimpleEsp8266.h>

const char *ssid = "YOUR WIFI SSID";
const char *pass = "YOUR WIFI PASS";

Servo servo_1;
const int IR1 = D1;
const int IR2 = D2;
const int parkir1 = D3;
const int parkir2 = D4;
const int parkir3 = D5;
int readIR1;
int readIR2;
int jumlah = 3;
int led1, led2, led3;

bool sudah_cetak = false;
bool sudah_cetak2 = false;
bool sudah_cetak_parkir1 = false;
bool sudah_cetak_parkir2 = false;
bool sudah_cetak_parkir3 = false;
bool printStatus = true;

void setup() {
  Serial.begin(115200);
  pinMode(IR1, INPUT);
  pinMode(IR2, INPUT);
  pinMode(parkir1, INPUT);
  pinMode(parkir2, INPUT);
  pinMode(parkir3, INPUT);
  servo_1.attach(D0, 500, 2500);
  connectWifi();
  Blynk.begin(BLYNK_AUTH_TOKEN, ssid, pass);
}

void connectWifi() {
  Serial.print("Connecting to WiFi");
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, pass);

  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println();
  delay(2000);
}

void sentData(int slot, bool terdeteksi) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    String address = "http://ip_address/parkir/record.php?slot=";
    address += String(slot);
    address += "&status=";
    address += terdeteksi ? "masuk" : "keluar";

    http.begin(client, address);
    int httpCode = http.GET();
    String payload;

    if (httpCode > 0) {
      payload = http.getString();
      payload.trim();
      if (payload.length() > 0) {
        Serial.println(payload + "\n");
      }
    }

    http.end();
  } else {
    Serial.print("Not connected to wifi ");
    Serial.println(ssid);
    connectWifi();
  }
}

void loop() {
  Blynk.virtualWrite(V12, jumlah);
  readIR1 = digitalRead(IR1);
  readIR2 = digitalRead(IR2);

  // Slot Parkir 1
  if (digitalRead(parkir1) == HIGH) {
    if (sudah_cetak_parkir1) {
      sentData(1, false);
      sudah_cetak_parkir1 = false;
      sensorvalue1(0);
    }
    Serial.println("Parkir 1 Kosong");
  } else {
    if (!sudah_cetak_parkir1) {
      sentData(1, true);
      Serial.println("Parkir 1 Terisi");
      sudah_cetak_parkir1 = true;
      sensorvalue1(1);
    }
  }

  // Slot Parkir 2
  if (digitalRead(parkir2) == HIGH) {
    if (sudah_cetak_parkir2) {
      sentData(2, false);
      sudah_cetak_parkir2 = false;
      sensorvalue2(0);
    }
    Serial.println("Parkir 2 Kosong");
  } else {
    if (!sudah_cetak_parkir2) {
      sentData(2, true);
      Serial.println("Parkir 2 Terisi");
      sudah_cetak_parkir2 = true;
      sensorvalue2(1);
    }
  }

  // Slot Parkir 3
  if (digitalRead(parkir3) == HIGH) {
    if (sudah_cetak_parkir3) {
      sentData(3, false);
      sudah_cetak_parkir3 = false;
      sensorvalue3(0);
    }
    Serial.println("Parkir 3 Kosong");
  } else {
    if (!sudah_cetak_parkir3) {
      sentData(3, true);
      Serial.println("Parkir 3 Terisi");
      sudah_cetak_parkir3 = true;
      sensorvalue3(1);
    }
  }

  ServoControlBuka(readIR1);
  ServoControlTutup(readIR2);
  Blynk.run();
  delay(2000);
}

void ServoControlBuka(int readIR1) {
  if (jumlah > 0) {
    if (readIR1 == 0) {
      servo_1.write(0);

      if (!sudah_cetak) {
        delay(1500);
        jumlah--;
        sudah_cetak = true;
      }
    } else {
      servo_1.write(90);
      sudah_cetak = false;
    }
  } else {
    Blynk.logEvent("Parkir Penuh!");
    servo_1.write(90);
    delay(1500);
  }
}

void ServoControlTutup(int readIR1) {
  if (readIR2 == 0 && jumlah < 3) {
    servo_1.write(0);

    if (!sudah_cetak2) {
      delay(1500);
      jumlah++;
      sudah_cetak2 = true;
    } else {
      servo_1.write(90);
      sudah_cetak2 = false;
    }
  }
}

void sensorvalue1(int led1) {
  int sdata = led1;
  Blynk.virtualWrite(V9, sdata);
}

void sensorvalue2(int led2) {
  int sdata = led2;
  Blynk.virtualWrite(V10, sdata);
}

void sensorvalue3(int led3) {
  int sdata = led3;
  Blynk.virtualWrite(V11, sdata);
}