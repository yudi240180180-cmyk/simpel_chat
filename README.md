# 💬 Real-Time Dynamic Chat Application

Aplikasi obrolan *real-time* berbasis web yang dibangun menggunakan **Laravel 11**, **Laravel Breeze** (Autentikasi), **Livewire/Blade**, dan **Laravel Reverb** sebagai server WebSocket lokal. Aplikasi ini mendukung komunikasi instan antar-pengguna, pembuatan ruang obrolan pribadi secara otomatis, serta pembuatan grup diskusi dinamis.

---

## ✨ Fitur Utama

* **Autentikasi Pengguna & Alur Bersih**: Pengguna wajib melakukan registrasi akun baru terlebih dahulu untuk mengakses aplikasi, dan akan langsung diarahkan ke halaman utama obrolan setelah sukses masuk.
* **Pencatatan Riwayat Chat Permanen**: Setiap pesan disimpan aman di dalam database MySQL sehingga obrolan hari kemarin dan seterusnya tidak akan hilang.
* **Pembatas Tanggal Dinamis (Groupby Day)**: Antarmuka chat otomatis memisahkan balon obrolan dengan label waktu seperti `"Hari Ini"`, `"Kemarin"`, atau tanggal spesifik (misal: `"19 Mei 2026"`).
* **Obrolan Pribadi Otomatis**: Ketika user baru mendaftar, sistem otomatis membuatkan jalur obrolan privat dengan seluruh pengguna lain yang sudah terdaftar.
* **Grup Chat Dinamis**: Pengguna dapat membuat grup baru, menentukan nama grup, dan memilih beberapa anggota sekaligus untuk bergabung dalam ruang obrolan kelompok.
* **Status Kehadiran Real-Time (Presence Channel)**: Melacak dan menampilkan daftar anggota yang sedang *online* di dalam *room* yang sedang aktif secara instan.

---

## 🛠️ Prasyarat Sistem

Sebelum menjalankan proyek, pastikan komputer Anda sudah terpasang:
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL / XAMPP

---

## 🚀 Cara Menjalankan Aplikasi di Lokal

### 1. Kloning Repositori & Instalasi Dependensi
```bash
# Isi dependensi PHP
composer install

# Isi dependensi Frontend (JavaScript/Tailwind)
npm install