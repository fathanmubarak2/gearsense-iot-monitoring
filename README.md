# ⚙️ GEARSENSE: IoT & Edge-AI Gear Vibration Monitoring System

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![ESP32](https://img.shields.io/badge/Hardware-ESP32-E7352C?style=for-the-badge&logo=espressif&logoColor=white)](https://www.espressif.com/)
[![Sensor](https://img.shields.io/badge/Sensor-ADXL345-0071C5?style=for-the-badge)](https://www.analog.com/)
[![Telegram](https://img.shields.io/badge/Alerts-Telegram_Bot-26A5E4?style=for-the-badge&logo=telegram&logoColor=white)](https://telegram.org)
[![Standard](https://img.shields.io/badge/Standard-ISO_10816--3-059669?style=for-the-badge)](#standar-evaluasi-getaran-iso-10816-3)

**GEARSENSE** adalah sistem pemantauan kondisi getaran roda gigi (*Condition-Based Monitoring & Predictive Maintenance*) pada transmisi konveyor berbasis **Internet of Things (IoT)** dan **Edge Computing**. Sistem ini memanfaatkan mikrokontroler **ESP32** dan sensor akselerometer tri-aksial **ADXL345** untuk mendeteksi getaran, mengekstraksi fitur statistik domain waktu, mengklasifikasi kondisi transmisi roda gigi secara *real-time*, serta mengirimkan peringatan dini anomali melalui **Bot Telegram**.

---

## 🌟 Fitur Utama Sistem

1. **Edge Feature Extraction pada Mikrokontroler ESP32:**
   - Ekstraksi parameter statistik getaran secara lokal pada mikrokontroler: *RMS (Root Mean Square)*, *Peak-to-Peak (P2P)*, *Wilson Amplitude (WAMP)*, *Kurtosis*, dan *Skewness*.
   - Filter gravitasi dinamis (eliminasi komponen statis 1G sumbu Z) untuk memperoleh getaran murni mekanis transmisi.
2. **Klasifikasi Kondisi Berbasis Standar & Logika Inferensi:**
   - Mengelompokkan kondisi operasi roda gigi menjadi 3 tingkatan status:
     - 🟢 **NORMAL (Zona A):** Kondisi transmisi optimal, getaran rendah ($Z_{\text{RMS}} < 0.06\text{ g}$, $P2P_Z < 0.12\text{ g}$).
     - 🟡 **SCORING / TERKIKIS (Zona B/C):** Peringatan keausan / erosi permukaan gigi ($0.06\text{ g} \le Z_{\text{RMS}} < 0.12\text{ g}$ atau $P2P_Z \ge 0.12\text{ g}$). Rekomendasi pelumasan CBM.
     - 🔴 **DAMAGED / RUSAK KRITIS (Zona D):** Kerusakan retak / patah gigi transmisi ($Z_{\text{RMS}} \ge 0.12\text{ g}$ atau $P2P_Z \ge 0.30\text{ g}$). Konveyor harus segera dihentikan.
3. **Dashboard Web Telemetri Real-Time:**
   - **Dashboard Utama:** Ringkasan KPI konveyor, status konektivitas IoT, metrik getaran tri-aksial, dan rekomendasi preskriptif CBM.
   - **Live Monitoring:** Osiloskop digital streaming data getaran 50 Hz, tabel klasifikasi *live*, dan indikator anomali.
   - **Riwayat Pengukuran:** Grafik tren fluktuasi historis 24 jam dengan batas acuan **ISO 10816-3** ($0.10\text{ g}$ Waspada & $0.15\text{ g}$ Bahaya), filter tabel cerdas, dan ekspor data CSV.
4. **Early Warning System (EWS) Bot Telegram:**
   - Otomasi pengiriman pesan peringatan instan ke akun atau grup teknisi saat insiden getaran abnormal terjadi.
   - Dilengkapi sistem *debounce cooldown* (30 detik) guna mencegah *spamming* dan *rate-limit* Telegram API.

---

## 🏗️ Arsitektur Sistem

```text
  ┌─────────────────────────────────────────────────────────────┐
  │                 TRANSMISI RODA GIGI KONVEYOR                │
  └──────────────────────────────┬──────────────────────────────┘
                                 │ Getaran Mekanis
                                 ▼
                     ┌───────────────────────┐
                     │ Sensor ADXL345 (I2C)  │
                     └───────────┬───────────┘
                                 │ 50 - 200 Hz Tri-Axial
                                 ▼
                     ┌───────────────────────┐
                     │ Node ESP32 (Edge AI)  │
                     │ • Fitur Statistik     │
                     │ • Deteksi Anomali     │
                     └───────────┬───────────┘
                                 │ HTTP POST JSON (WiFi)
                                 ▼
                ┌───────────────────────────────────┐
                │       SERVER WEB GEARSENSE        │
                │  • Laravel 10 Backend & REST API  │
                │  • Database MySQL / Telemetri     │
                │  • Dashboard Real-Time (Chart.js) │
                └─────────┬───────────────┬─────────┘
                          │               │
        Alert Notifikasi  │               │ Visualisasi Data
                          ▼               ▼
                ┌──────────────────┐    ┌──────────────────┐
                │   BOT TELEGRAM   │    │  BROWSER CLIENT  │
                │  (@pata77_bot)   │    │ (PC / Smartphone)│
                └──────────────────┘    └──────────────────┘
```

---

## 🔌 Skema Wiring Hardware (ESP32 ke ADXL345)

Komunikasi antara ESP32 dan ADXL345 menggunakan protokol **I2C Fast Mode (400 kHz)** pada alamat default **`0x53`**:

| Pin ADXL345 | Pin ESP32 (DOIT V1) | Keterangan |
| :--- | :--- | :--- |
| **VCC** | **3.3V** | Tegangan operasi modul ADXL345 (Gunakan 3.3V) |
| **GND** | **GND** | Ground bersama |
| **CS** | **3.3V** | Mengaktifkan mode komunikasi I2C |
| **SDO** | **GND** | Mengatur alamat I2C ke `0x53` |
| **SDA** | **GPIO 21 (D21)** | Jalur Data Serial I2C |
| **SCL** | **GPIO 22 (D22)** | Jalur Clock Serial I2C |

> Panduan fisik wiring selengkapnya tersedia di: [`firmware/PANDUAN_HARDWARE_IOT.md`](firmware/PANDUAN_HARDWARE_IOT.md).

---

## 💻 Panduan Instalasi & Menjalankan Web

### 1. Prasyarat Sistem
* PHP >= 8.1 dengan ekstensi `pdo_mysql`, `curl`, `mbstring`, `openssl`
* Composer 2.x
* Database Server (MySQL / MariaDB via XAMPP / Laragon)

### 2. Kloning Repositori
```bash
git clone https://github.com/fathanmubarak2/gearsense.git
cd gearsense
```

### 3. Instal Dependensi
```bash
composer install
```

### 4. Konfigurasi Lingkungan (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database dan timezone pada file `.env`:
```env
APP_NAME=GearSense
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gearsense_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 6. Jalankan Server Web
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Buka browser pada: `http://localhost:8000` (atau IP laptop Anda pada jaringan lokal).

---

## 🚀 Panduan Pengunggahan Firmware ESP32

1. Buka aplikasi **Arduino IDE**.
2. Buka berkas: [`firmware/gearsense_firmware.ino`](firmware/gearsense_firmware.ino).
3. Pastikan *library* berikut telah terinstal pada Library Manager:
   * `Adafruit ADXL345` (oleh Adafruit)
   * `Adafruit Unified Sensor` (oleh Adafruit)
   * `ArduinoJson` (oleh Benoit Blanchon, v6.x)
4. Sesuaikan konstanta koneksi pada bagian atas kode:
   ```cpp
   const char* WIFI_SSID     = "NAMA_WIFI_ATAU_HOTSPOT_ANDA";
   const char* WIFI_PASSWORD = "PASSWORD_WIFI_ANDA";
   const char* SERVER_BASE_URL = "http://192.168.1.XXX:8000"; // IP Laptop Anda
   ```
5. Pilih Board: **ESP32 Dev Module** dan Port COM yang sesuai.
6. Klik **Upload**. Pantau proses melalui Serial Monitor (Baud Rate: `115200`).

---

## 📡 Dokumentasi Endpoint Telemetri Sensor

* **Endpoint:** `POST /api/telemetry`
* **Format:** `application/json`
* **Contoh Payload:**
```json
{
  "node_code": "ESP32-GEARSENSE-01",
  "raw_x": 0.045,
  "raw_y": -0.012,
  "raw_z": 0.985,
  "p2p_x": 0.082,
  "p2p_y": 0.065,
  "p2p_z": 0.098,
  "wamp_z": 0.040,
  "wamp_y": 0.020,
  "rms_z": 0.018,
  "kurtosis_z": 3.12,
  "skewness_z": 0.15,
  "condition": "NORMAL",
  "confidence_score": 98.4,
  "fis_score": 14.5,
  "sampling_rate_hz": 200
}
```

---

## 🎓 Atribusi Akademik & Pengembang

Projek ini dikembangkan sebagai bagian dari penelitian tugas akhir / skripsi:
* **Institusi:** Program Studi Informatika / Teknik Informatika, Fakultas Teknologi Informasi, Universitas YARSI.
* **Fokus Penelitian:** Sistem Monitoring Getaran Roda Gigi dan Klasifikasi Kerusakan Transmisi Konveyor Berbasis IoT (*Condition-Based Monitoring & Predictive Maintenance*).

---

## 📄 Lisensi

Projek ini dilisensikan di bawah lisensi [MIT License](LICENSE).
