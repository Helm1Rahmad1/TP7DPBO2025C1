
# TP7DPBO2025C1

## Janji
Saya Muhammad Helmi Rahmadi dengan NIM 2311574 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## Desain Program

<img width="885" alt="Screenshot 2025-04-20 at 14 13 59" src="https://github.com/user-attachments/assets/d371add9-1aef-4f86-bf79-4c52d7e019da" />

## Deskripsi Program
Sistem Manajemen Galeri Seni adalah PHP berbasis OOP dengan arsitektur MVC yang memungkinkan pengguna untuk mengelola informasi tentang seniman, karya seni, dan pameran.ni menggunakan PDO (PHP Data Objects) untuk koneksi database yang aman dan prepared statements untuk mencegah SQL injection.

Fitur utama meliputi:
- Manajemen data seniman (tambah, lihat, edit, hapus)
- Manajemen karya seni dengan relasi ke seniman (tambah, lihat, edit, hapus)
- Manajemen pameran dengan relasi ke karya seni (tambah, lihat, edit, hapus)
- Fitur pencarian pada setiap modul
- Tampilan responsif dan user-friendly
- Validasi input dan feedback pengguna

## Struktur Kelas
Struktur ini dirancang dengan pendekatan OOP yang terdiri dari beberapa kelas utama:

1. **Database**
   - Kelas untuk mengelola koneksi database
   - Menggunakan singleton pattern untuk memastikan hanya ada satu koneksi

2. **Seniman**
   - Kelas untuk mengelola data seniman
   - Atribut: id_seniman, nama, asal, gaya_seni
   - Metode: getAll(), getById(), save(), update(), delete(), search()

3. **Karya**
   - Kelas untuk mengelola data karya seni
   - Atribut: id_karya, judul, id_seniman, tahun
   - Metode: getAll(), getById(), getByArtist(), save(), update(), delete(), search()

4. **Pameran**
   - Kelas untuk mengelola data pameran
   - Atribut: id_pameran, id_karya, tempat, tanggal
   - Metode: getAll(), getById(), getByArtwork(), save(), update(), delete(), search()

Relasi antar kelas:
- Satu **Seniman** dapat memiliki banyak **Karya** (one-to-many)
- Satu **Karya** dapat ditampilkan di banyak **Pameran** (one-to-many)

## Alur Program
1. User mengakses melalui `index.php`
2. Controller pada `index.php` memanggil view yang sesuai berdasarkan parameter `page`
3. View berinteraksi dengan model (class) untuk mendapatkan atau memanipulasi data
4. Model berkomunikasi dengan database menggunakan PDO
5. Hasil proses ditampilkan kembali ke user melalui view

## Cara Menjalankan Program
1. Clone repositori ini ke direktori web server 
2. Import file database `db_galeri.sql` ke MySQL/MariaDB
3. Sesuaikan konfigurasi database di `config/db.php` jika diperlukan
4. Akses melalui web browser (contoh: (http://localhost/Galeri%20Seni/index.php)

## Struktur Folder
```
Galeri_Seni/
├── class/
│   ├── Seniman.php
│   ├── Karya.php
│   └── Pameran.php
├── config/
│   └── db.php
├── database/
│   └── db_galeri.sql
├── view/
│   ├── header.php
│   ├── footer.php
│   ├── seniman.php
│   ├── karya.php
│   └── pameran.php
├── index.php
└── style.css
```

## Dokumentasi

https://github.com/user-attachments/assets/dcfb399c-ab1e-41a6-8f80-add468e75c5e

---
