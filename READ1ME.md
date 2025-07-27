# Sistem Informasi Apotek Kita Farma

Sistem Informasi Apotek Kita Farma adalah aplikasi berbasis web yang dibangun menggunakan framework CodeIgniter 4 untuk mengelola operasional apotek, termasuk manajemen obat, supplier, member, dan transaksi penjualan.

## Daftar Isi
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
  - [Persiapan XAMPP](#persiapan-xampp)
  - [Instalasi Aplikasi](#instalasi-aplikasi)
  - [Konfigurasi Database](#konfigurasi-database)
- [Penggunaan](#penggunaan)
  - [Login](#login)
  - [Fitur-fitur](#fitur-fitur)
- [Struktur Aplikasi](#struktur-aplikasi)
- [Troubleshooting](#troubleshooting)

## Persyaratan Sistem

- XAMPP versi 8.2.12 (xampp-windows-x64-8.2.12-0-VS16-installer)
- PHP 8.2 atau lebih tinggi (sudah termasuk dalam XAMPP)
- MySQL 8.0 atau lebih tinggi (sudah termasuk dalam XAMPP)
- Browser web modern (Chrome, Firefox, Edge, dll.)
- Composer (untuk instalasi CodeIgniter 4)

## Instalasi

### Persiapan XAMPP

1. **Download XAMPP**
   - Download XAMPP versi 8.2.12 (xampp-windows-x64-8.2.12-0-VS16-installer) dari [situs resmi Apache Friends](https://www.apachefriends.org/download.html)

2. **Instalasi XAMPP**
   - Jalankan installer XAMPP yang telah didownload
   - Ikuti petunjuk instalasi (disarankan menggunakan pengaturan default)
   - Pastikan komponen Apache, MySQL, dan PHP tercentang
   - Selesaikan proses instalasi

3. **Menjalankan XAMPP**
   - Buka XAMPP Control Panel
   - Start modul Apache dan MySQL
   - Pastikan kedua modul berjalan dengan baik (indikator berwarna hijau)

### Instalasi Aplikasi

1. **Persiapan Folder Proyek**
   - Buka folder `C:\xampp\htdocs`
   - Buat folder baru dengan nama `apotek-kita-farma`

2. **Download Proyek**
   - Download file zip proyek dan ekstrak ke folder `C:\xampp\htdocs\apotek-kita-farma`

### Login

Setelah instalasi selesai dan server XAMPP aktif, ikuti langkah berikut untuk masuk ke sistem:

1. Buka browser dan akses alamat:  
   **`http://localhost:8080`**

2. Anda akan diarahkan ke halaman login

3. Gunakan data login berikut:

   | Username   | Password  |
   |------------|-----------|
   | kevin_bimo | kevin123  |

4. Klik tombol **Masuk**.

5. Jika data benar, Anda akan diarahkan ke halaman **Dashboard** utama sistem Apotek Kita Farma.

> 💡 **Catatan:** Data akun ini terdapat dalam tabel `user` pada database. Anda dapat menambahkan atau memodifikasi pengguna melalui phpMyAdmin.

