#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_ADXL345_U.h>
#include <ArduinoJson.h>

/* =========================================================================================
 * 1. PENGATURAN KONEKSI WIFI & ALAMAT SERVER WEB LARAVEL
 * ========================================================================================= */
// Ganti dengan Nama (SSID) dan Password Hotspot HP / WiFi Anda
const char* WIFI_SSID     = "DESKTOP-63TMT41 4341";
const char* WIFI_PASSWORD = "R236(k89";

// Masukkan IP Laptop Anda (Cek di PowerShell / CMD dengan mengetik 'ipconfig')
// Contoh: "http://192.168.1.10:8000" (Gunakan port 8000 default Laravel)
const char* SERVER_BASE_URL = "http://192.168.137.1:8000";

// Identitas Node Sensor (Sesuai database Gearsense)
const char* NODE_CODE       = "ESP32-GEARSENSE-01";

/* =========================================================================================
 * 2. KONFIGURASI PIN & SENSOR
 * ========================================================================================= */
#define I2C_SDA_PIN        21   // Pin D21 ESP32 ke Pin SDA ADXL345
#define I2C_SCL_PIN        22   // Pin D22 ESP32 ke Pin SCL ADXL345
#define LED_INDICATOR_PIN   2   // Onboard LED ESP32 (Berkedip saat mengirim data)

// Inisialisasi Sensor ADXL345
Adafruit_ADXL345_Unified accel = Adafruit_ADXL345_Unified(12345);

// Parameter Sampling Getaran
const int SAMPLING_WINDOW_SIZE = 50;   // 50 sampel per siklus pengiriman
const int SAMPLING_DELAY_MS    = 5;    // ~200 Hz frekuensi pengambilan sampel (5ms)
const float WAMP_THRESHOLD     = 0.10; // Ambang batas Wilson Amplitude (0.10 g)

/* =========================================================================================
 * SETUP() - Dijalankan Sekali Saat ESP32 Menyala
 * ========================================================================================= */
void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("\n============================================================");
  Serial.println("  GEARSENSE IoT: Node Pemantau Getaran Roda Gigi (ESP32)   ");
  Serial.println("  Fakultas Teknologi Informasi - Universitas YARSI          ");
  Serial.println("============================================================");

  pinMode(LED_INDICATOR_PIN, OUTPUT);
  digitalWrite(LED_INDICATOR_PIN, LOW);

  // 1. Inisialisasi Komunikasi I2C dan Sensor ADXL345
  Wire.begin(I2C_SDA_PIN, I2C_SCL_PIN);
  Wire.setClock(400000); // 400 kHz Fast I2C Bus

  Serial.print("[ADXL345] Menghubungkan ke sensor getaran ADXL345 (0x53)... ");
  if (!accel.begin()) {
    Serial.println("GAGAL!");
    Serial.println("[ADXL345] PERINGATAN: Sensor tidak terdeteksi. Periksa kembali kabel SDA (21) dan SCL (22).");
  } else {
    Serial.println("BERHASIL!");
    accel.setRange(ADXL345_RANGE_16_G);        // Range +-16G (Standar getaran industri)
    accel.setDataRate(ADXL345_DATARATE_200_HZ); // Kecepatan sampling 200 Hz
    Serial.println("[ADXL345] Konfigurasi: Range +-16G | Data Rate: 200 Hz.");
  }

  // 2. Menghubungkan ke Jaringan WiFi
  Serial.printf("\n[WiFi] Menghubungkan ke: %s ", WIFI_SSID);
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 25) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WiFi] Berhasil Terhubung!");
    Serial.printf("[WiFi] IP Address ESP32 : %s\n", WiFi.localIP().toString().c_str());
    Serial.printf("[WiFi] Kekuatan Sinyal  : %d dBm\n", WiFi.RSSI());
  } else {
    Serial.println("\n[WiFi] Belum terhubung. Pastikan SSID dan Password sesuai!");
  }

  Serial.println("\n[GEARSENSE] Inisialisasi Selesai. Memulai pembacaan getaran...\n");
}

/* =========================================================================================
 * LOOP() - Pengambilan Sinyal Getaran, Ekstraksi Fitur & Pengiriman Data
 * ========================================================================================= */
void loop() {
  // Cek koneksi WiFi otomatis
  if (WiFi.status() != WL_CONNECTED) {
    Serial.print("[WiFi] Menyambung ulang... ");
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  }

  // 1. Verifikasi Keberadaan Sensor ADXL345 di Bus I2C (Alamat 0x53)
  Wire.beginTransmission(0x53);
  byte i2cStatus = Wire.endTransmission();

  if (i2cStatus != 0) {
    // Sensor dicabut / kabel lepas!
    Serial.printf("[ADXL345 ERROR] Sensor terputus atau kabel lepas (I2C Code: %d)! Menunggu sensor dipasang kembali...\n", i2cStatus);
    digitalWrite(LED_INDICATOR_PIN, LOW); // Matikan lampu LED

    // Coba inisialisasi ulang jika baru saja dicolokkan kembali
    accel.begin();
    delay(1000);
    return; // Hentikan transmisi data palsu ke web saat sensor dicabut
  }

  float sumZ = 0.0;
  float sumZSquare = 0.0;
  float minZ = 999.0, maxZ = -999.0;
  float minX = 999.0, maxX = -999.0;
  float minY = 999.0, maxY = -999.0;

  float lastZ = 0.0, lastY = 0.0;
  int wampCountZ = 0, wampCountY = 0;

  float lastRawX = 0.0, lastRawY = 0.0, lastRawZ = 0.0;
  float samplesZ[SAMPLING_WINDOW_SIZE];

  // 1. Pengambilan 50 Sampel Getaran Berurutan
  for (int i = 0; i < SAMPLING_WINDOW_SIZE; i++) {
    sensors_event_t event;
    accel.getEvent(&event);

    // Konversi percepatan dari m/s^2 ke satuan g (1 g = 9.80665 m/s^2)
    float ax = event.acceleration.x / 9.80665;
    float ay = event.acceleration.y / 9.80665;
    float az = event.acceleration.z / 9.80665;

    samplesZ[i] = az;
    sumZ += az;
    sumZSquare += (az * az);

    if (az < minZ) minZ = az;
    if (az > maxZ) maxZ = az;
    if (ax < minX) minX = ax;
    if (ax > maxX) maxX = ax;
    if (ay < minY) minY = ay;
    if (ay > maxY) maxY = ay;

    // Wilson Amplitude
    if (i > 0) {
      if (abs(az - lastZ) >= WAMP_THRESHOLD) wampCountZ++;
      if (abs(ay - lastY) >= WAMP_THRESHOLD) wampCountY++;
    }
    lastZ = az;
    lastY = ay;

    lastRawX = ax;
    lastRawY = ay;
    lastRawZ = az;

    delay(SAMPLING_DELAY_MS);
  }

    // 2. Ekstraksi Fitur Statistik Domain Waktu
  float meanZ = sumZ / SAMPLING_WINDOW_SIZE;
  float p2pZ  = maxZ - minZ;
  float p2pX  = maxX - minX;
  float p2pY  = maxY - minY;
  float wampZ = (float)wampCountZ / (float)SAMPLING_WINDOW_SIZE;
  float wampY = (float)wampCountY / (float)SAMPLING_WINDOW_SIZE;

  // Kurtosis, Skewness, dan Standar Deviasi (RMS Getaran Dinamis Sejati)
  float varianceZ = 0.0, sumSkew = 0.0, sumKurt = 0.0;
  for (int i = 0; i < SAMPLING_WINDOW_SIZE; i++) {
    float diff = samplesZ[i] - meanZ; // Menghilangkan offset gravitasi bumi (DC)
    varianceZ += (diff * diff);
    sumSkew   += (diff * diff * diff);
    sumKurt   += (diff * diff * diff * diff);
  }
  float stdDevZ = sqrt(varianceZ / SAMPLING_WINDOW_SIZE);
  if (stdDevZ < 0.0001) stdDevZ = 0.0001;

  // RMS Getaran Murni (Gunakan stdDevZ yang sudah bebas dari gravitasi 1G)
  float rmsZ = stdDevZ; 

  float kurtosisZ = (sumKurt / SAMPLING_WINDOW_SIZE) / (stdDevZ * stdDevZ * stdDevZ * stdDevZ);
  float skewnessZ = (sumSkew / SAMPLING_WINDOW_SIZE) / (stdDevZ * stdDevZ * stdDevZ);

  // 3. Evaluasi Kondisi Roda Gigi (Berdasarkan Nilai Dinamik Getaran Proyek Gearsense)
  String condition = "NORMAL";
  float confidenceScore = 98.4;
  float fisScore = 14.5;

  if (p2pZ >= 0.30 || rmsZ >= 0.12 || kurtosisZ > 5.5) {
    condition = "DAMAGED";
    confidenceScore = 99.2;
    fisScore = 88.5;
  } else if (p2pZ >= 0.12 || rmsZ >= 0.06 || kurtosisZ > 3.8) {
    condition = "SCORING";
    confidenceScore = 94.0;
    fisScore = 52.0;
  } else {
    condition = "NORMAL";
    confidenceScore = 98.4;
    fisScore = 14.5;
  }

  // 4. Kirim Data ke Server Web Gearsense
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String endpoint = String(SERVER_BASE_URL) + "/api/telemetry";

    http.begin(endpoint);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.setTimeout(3000);

    StaticJsonDocument<768> doc;
    doc["node_code"]        = NODE_CODE;
    doc["raw_x"]            = round(lastRawX * 1000.0) / 1000.0;
    doc["raw_y"]            = round(lastRawY * 1000.0) / 1000.0;
    doc["raw_z"]            = round(lastRawZ * 1000.0) / 1000.0;
    doc["p2p_x"]            = round(p2pX * 1000.0) / 1000.0;
    doc["p2p_y"]            = round(p2pY * 1000.0) / 1000.0;
    doc["p2p_z"]            = round(p2pZ * 1000.0) / 1000.0;
    doc["wamp_z"]           = round(wampZ * 1000.0) / 1000.0;
    doc["wamp_y"]           = round(wampY * 1000.0) / 1000.0;
    doc["rms_z"]            = round(rmsZ * 1000.0) / 1000.0;
    doc["kurtosis_z"]       = round(kurtosisZ * 100.0) / 100.0;
    doc["skewness_z"]       = round(skewnessZ * 100.0) / 100.0;
    doc["condition"]        = condition;
    doc["confidence_score"] = confidenceScore;
    doc["fis_score"]        = fisScore;
    doc["sampling_rate_hz"] = 200;

    String jsonPayload;
    serializeJson(doc, jsonPayload);

    int httpCode = http.POST(jsonPayload);

    if (httpCode == 200 || httpCode == 201) {
      digitalWrite(LED_INDICATOR_PIN, HIGH);
      Serial.printf("[KIRIM SUKSES] Z_RMS: %.3f g | P2P_Z: %.3f g | Kondisi: %s (Conf: %.1f%%)\n",
                    rmsZ, p2pZ, condition.c_str(), confidenceScore);
      digitalWrite(LED_INDICATOR_PIN, LOW);
    } else {
      Serial.printf("[HTTP ERROR %d] Gagal kirim ke %s. Pesan: %s\n",
                    httpCode, endpoint.c_str(), http.errorToString(httpCode).c_str());
    }
    http.end();
  } else {
    // Tampilkan di serial monitor jika mode offline
    Serial.printf("[OFFLINE] Z_RMS: %.3f g | P2P_Z: %.3f g | Kondisi: %s\n", rmsZ, p2pZ, condition.c_str());
  }

  delay(40); // Jeda pengiriman responsif (~3-4 paket per detik) untuk visualisasi mulus
}
