# 🛒 LitleMart — High-Performance Multi-Vendor Marketplace

LitleMart adalah platform marketplace multi-vendor modern yang dibangun dengan fokus pada performa tinggi, desain premium, dan pengalaman pengguna (UX) yang mulus di semua perangkat. Platform ini memungkinkan banyak penjual (vendor) untuk mengelola toko mereka sendiri, sementara pembeli dapat menikmati pengalaman belanja yang cepat, canggih, dan dapat diinstal seperti aplikasi native.

![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-orange?logo=mysql)
![Firebase](https://img.shields.io/badge/Firebase-Realtime-yellow?logo=firebase)
![PWA](https://img.shields.io/badge/PWA-Ready-purple?logo=pwa)
![License](https://img.shields.io/badge/License-MIT-green)

---

## ✨ Fitur Utama

### 🏬 E-Commerce & Multi-Vendor
- **Multi-Vendor Dashboard**: Panel manajemen lengkap (Produk, Pesanan, Analitik, Wallet).
- **Ongkir Kompetitif**: Perhitungan ongkos kirim otomatis via **RajaOngkir API**.
- **Integrated Payment**: Pembayaran aman menggunakan **Midtrans Snap API** + Webhook otomatis.
- **Order Tracking**: Sistem pelacakan pesanan dengan input nomor resi & konfirmasi penerimaan.
- **Auto-Complete Orders**: Pesanan otomatis selesai setelah 3 hari tanpa konfirmasi.
- **Vendor Wallet**: Manajemen keuangan vendor dengan pencairan saldo terproteksi.

### 🔔 Real-time & Notifikasi
- **Firebase Realtime Chat**: Pesan instan antara pembeli dan vendor dengan status baca & indikator mengetik.
- **Smart Notification Badges**: Badge pesanan aktif di navbar, hamburger menu, dan dashboard vendor.
- **Web Audio Notification**: Bunyi notifikasi menggunakan **Web Audio API** (synthesized tone) — tidak mengganggu atau menghentikan musik/media lain yang sedang diputar.
- **Toast Notification**: Notifikasi pop-up interaktif dengan deep-link langsung ke halaman relevan.

### 📱 Progressive Web App (PWA)
- **Installable**: Dapat diinstal di layar utama **Android & iOS** seperti aplikasi native.
- **Service Worker**: Caching aset statis (Cache-First) & data dinamis (Network-First) untuk performa offline.
- **App Manifest**: Nama, ikon, warna tema, dan mode layar penuh terkonfigurasi.

### 🎨 UX & Desain Premium
- **Global Loading Skeleton**: Shimmer skeleton loader muncul otomatis di semua halaman saat navigasi — terlihat di semua perangkat dan dimensi layar.
- **Pull-to-Refresh Global**: Tarik halaman dari atas untuk me-refresh di semua halaman (mode mobile).
- **Infinite Scroll**: Produk di halaman utama dimuat otomatis saat mendekati dasar halaman (desktop).
- **Responsive Design**: Antarmuka premium yang dioptimalkan untuk Mobile dan Desktop.
- **Chat Room Mobile-Optimized**: Header nama lawan bicara terkunci, keyboard tidak menggeser layout.

### 🚀 Performa & Optimasi
- **Database Indexing**: Indeks pada kolom kritis (`orders`, `order_items`, `notifications`, dll).
- **N+1 Query Fix**: Query halaman pesanan dikurangi dari 30+ menjadi 4 query tunggal.
- **Non-blocking Notifications**: Firebase dijalankan di luar siklus utama PHP (~300ms lebih cepat).
- **Google OAuth**: Login dengan Google menggunakan redirect URI dinamis (mendukung ngrok/dev).

---

## 🛠️ Teknologi

| Kategori | Teknologi |
|:---|:---|
| **Backend** | PHP 8.2, Custom MVC Architecture |
| **Database** | MySQL 8.0+, PDO |
| **Frontend** | TailwindCSS, Alpine.js, Vanilla JS |
| **Realtime** | Firebase Realtime Database |
| **Payment** | Midtrans Snap API |
| **Shipping** | RajaOngkir API |
| **Auth** | Google OAuth 2.0 |
| **PWA** | Service Worker, Web App Manifest |
| **Audio** | Web Audio API |

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- MySQL / MariaDB
- Composer
- XAMPP / Laragon (Apache/Nginx)
- HTTPS (diperlukan untuk fitur PWA penuh di perangkat nyata)

---

## 🚀 Cara Instalasi

1. **Clone Repositori**
   ```bash
   git clone https://github.com/akuuuel/LitleMart.git
   cd LitleMart
   ```

2. **Install Dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi Database**
   - Buat database baru di phpMyAdmin bernama `litlemart`.
   - Impor `database/migration.sql` (Skema Master).
   - **PENTING**: Impor `database/add_indexes.sql` untuk mengaktifkan optimasi performa query.

4. **Setup Environment**
   Buat file `.env` di root project:
   ```env
   DB_HOST=localhost
   DB_DATABASE=litlemart
   DB_USERNAME=root
   DB_PASSWORD=

   RAJAONGKIR_API_KEY=your_rajaongkir_key
   MIDTRANS_SERVER_KEY=your_midtrans_server_key
   MIDTRANS_CLIENT_KEY=your_midtrans_client_key
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   ```

5. **Jalankan Project**
   Akses melalui Virtual Host atau `http://localhost/LitleMart/public`.

---

## 📊 Catatan Teknis

### PWA
- Service Worker menggunakan strategi **Network-First** untuk halaman dinamis dan **Cache-First** untuk aset statis.
- PWA "Add to Home Screen" hanya muncul di perangkat nyata via **HTTPS**.
- Path di `manifest.json` menggunakan `./` agar kompatibel dengan deployment di subfolder (XAMPP).

### Audio Notifikasi
- Menggunakan **Web Audio API Oscillator** (bukan file MP3) sehingga tidak memicu interupsi sesi audio OS/browser.
- Notifikasi berupa nada sintetis double-beep (880Hz → 1100Hz).

### Chat Real-time
- Ditenagai oleh **Firebase Realtime Database**.
- Mendukung: status online/offline, indikator mengetik, status baca (✓✓).

---

## 📜 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

**Made with ❤️ by [akuuuel](https://github.com/akuuuel)**
