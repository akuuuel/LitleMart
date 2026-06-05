# 🛒 LitleMart — High-Performance Multi-Vendor Marketplace

LitleMart adalah platform marketplace multi-vendor modern yang dibangun dengan fokus pada performa tinggi, desain premium, dan arsitektur yang bersih. Platform ini memungkinkan banyak penjual (vendor) untuk mengelola toko mereka sendiri, sementara pembeli dapat menikmati pengalaman belanja yang cepat dan mulus.

![LitleMart Banner](https://img.shields.io/badge/Performance-Optimized-brightgreen.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)
![UI](https://img.shields.io/badge/UI-TailwindCSS-06B6D4.svg)

---

## ✨ Fitur Utama

- **🏬 Multi-Vendor Dashboard**: Panel manajemen lengkap untuk vendor (Produk, Pesanan, Analitik).
- **💳 Integrated Payment Gateway**: Pembayaran aman menggunakan **Midtrans Snap API**.
- **🚚 Real-time Shipping**: Perhitungan ongkos kirim otomatis via **RajaOngkir API**.
- **🔔 Real-time Notifications**: Notifikasi instan menggunakan **Firebase Realtime Database**.
- **🚀 Performance Optimized**: 
  - Penyelesaian masalah N+1 Query.
  - Implementasi Indexing Database pada kolom kritis.
  - Push Notifikasi Non-blocking (Fire-and-forget).
- **🛡️ Secure Financials**: Sistem Wallet Vendor dengan transaksi terproteksi.
- **📱 Responsive Design**: Antarmuka premium yang mendukung Mobile dan Desktop.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 8.2 (Custom MVC Architecture)
- **Database**: MySQL 8.0+
- **Frontend**: TailwindCSS, Alpine.js, Vanilla JS
- **Integration**: Midtrans (Payment), RajaOngkir (Shipping), Firebase (Notifications)
- **Tools**: Composer, PDO for DB Security

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- MySQL / MariaDB
- Composer
- XAMPP / Laragon (Apache/Nginx)

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
   - Impor file `database/migration.sql` (Skema Master).
   - **PENTING**: Impor file `database/add_indexes.sql` untuk mengaktifkan optimasi kecepatan query.

4. **Setup Environment**
   - Salin file `.env.example` (jika ada) atau buat file `.env` baru di root:
   ```env
   DB_HOST=localhost
   DB_DATABASE=litlemart
   DB_USERNAME=root
   DB_PASSWORD=
   
   RAJAONGKIR_API_KEY=your_api_key
   MIDTRANS_SERVER_KEY=your_server_key
   ```

5. **Jalankan Project**
   Akses di browser melalui Virtual Host atau `http://localhost/LitleMart/public`.

---

## 📊 Optimasi Performa Terbaru

Project ini telah melewati audit performa mendalam dengan hasil sebagai berikut:
- **Optimasi Database**: Query halaman pesanan dikurangi dari 30+ query menjadi hanya 4 query tunggal.
- **Badge Notifikasi**: Implementasi badge pintar pada menu hamburger dan dashboard vendor untuk pengingat pesanan aktif.
- **Kecepatan API**: Integrasi Firebase dioptimalkan agar tidak memblokir proses PHP (lebih cepat ~300ms per notifikasi).

---

## 📜 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

**Made with ❤️ by [akuuuel](https://github.com/akuuuel)**
