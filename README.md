<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="350" alt="Laravel Logo">
  </a>
</p>

<h1 align="center">💬 Real-Time Dynamic Chat Application</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-ED4931?style=for-the-badge&logo=laravel" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38BDF8?style=for-the-badge&logo=tailwind-css" alt="Tailwind Version">
  <img src="https://img.shields.io/badge/WebSocket-Reverb-4F46E5?style=for-the-badge&logo=laravel" alt="Reverb">
</p>

---

Aplikasi obrolan *real-time* berbasis web yang dibangun menggunakan **Laravel 11**, **Laravel Breeze** (Sistem Autentikasi), **Tailwind CSS/Blade**, dan **Laravel Reverb** sebagai server WebSocket lokal berkecepatan tinggi. Aplikasi ini mendukung komunikasi instan antar-pengguna, pemetaan ruang obrolan pribadi secara otomatis, serta pembuatan grup diskusi dinamis.

---

## ✨ Fitur Utama

* **Autentikasi Pengguna & Alur Bersih**: Pengguna wajib melakukan registrasi akun baru terlebih dahulu untuk mengakses aplikasi, dan akan langsung diarahkan ke halaman utama obrolan setelah sukses masuk.
* **Pencatatan Riwayat Chat Permanen**: Setiap pesan disimpan aman di dalam database MySQL sehingga obrolan hari kemarin dan seterusnya tidak akan hilang saat di-*refresh*.
* **Pembatas Tanggal Dinamis (Groupby Day)**: Antarmuka chat otomatis memisahkan balon obrolan dengan label waktu interaktif seperti `"Hari Ini"`, `"Kemarin"`, atau tanggal spesifik (misal: `"19 Mei 2026"`).
* **Obrolan Pribadi Otomatis**: Ketika user baru mendaftar, sistem backend secara otomatis membuatkan jalur obrolan privat dengan seluruh pengguna lain yang sudah terdaftar sebelumnya.
* **Grup Chat Dinamis**: Pengguna dapat membuat grup baru, menentukan nama kelompok, dan memilih beberapa anggota sekaligus untuk digabungkan ke ruang obrolan kelompok secara instan.
* **Status Kehadiran Real-Time (Presence Channel)**: Melacak dan menampilkan daftar anggota yang sedang *online* di dalam *room* yang sedang aktif secara instan menggunakan fitur penyiaran event (*event broadcasting*).

---

## 🛠️ Prasyarat Sistem

Sebelum menjalankan proyek di komputer lokal, pastikan lingkungan Anda sudah terpasang:
* **PHP** >= 8.2
* **Composer**
* **Node.js** & **NPM**
* **MySQL** / **XAMPP Server**

---

## 🚀 Cara Menjalankan Aplikasi di Lokal

### 1. Kloning Repositori & Instalasi Dependensi
Buka terminal proyek Anda dan jalankan perintah:
```bash
# Instalasi dependensi backend PHP
composer install

# Instalasi dependensi frontend JavaScript & CSS
npm install