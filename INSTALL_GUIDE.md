# Panduan Instalasi Aplikasi Pendaftaran Siswa Baru

## 🚀 Cara Instalasi

### 1. Persiapan Server
- Pastikan XAMPP atau WAMP sudah terinstall dan berjalan
- Aktifkan service Apache dan MySQL
- Pastikan MySQL berjalan di port 3306 (default)

### 2. Setup File
1. **Extract/Copy** semua file ke folder htdocs (XAMPP) atau www (WAMP)
   ```
   C:\xampp\htdocs\PWEB-SQL\
   ```

2. **Buka browser** dan akses:
   ```
   http://localhost/PWEB-SQL/
   ```

### 3. Instalasi Database

#### Pilihan 1: Instalasi Otomatis (Recommended)
1. Akses: `http://localhost/PWEB-SQL/php/install_fixed.php`
2. Script akan otomatis:
   - Membuat database `pendaftaran_siswa`
   - Membuat semua tabel yang diperlukan
   - Menambahkan admin default
   - Mengisi pengaturan default

#### Pilihan 2: Test Koneksi Dulu
Jika ada masalah, test dulu dengan:
```
http://localhost/PWEB-SQL/test_install.php
```

#### Pilihan 3: Instalasi Manual
1. Buka phpMyAdmin
2. Buat database baru: `pendaftaran_siswa`
3. Import file: `sample_data.sql`

### 4. Login Admin Default
Setelah instalasi berhasil:
- **Username:** admin
- **Password:** admin123
- **URL Admin:** `http://localhost/PWEB-SQL/admin.html`

⚠️ **PENTING:** Segera ubah password default setelah login pertama!

### 5. Konfigurasi Database (Jika diperlukan)
Edit file `php/config.php` jika menggunakan setting database yang berbeda:
```php
private $host = 'localhost';      // Server database
private $db_name = 'pendaftaran_siswa'; // Nama database
private $username = 'root';       // Username MySQL
private $password = '';           // Password MySQL
```

## 🔧 Troubleshooting

### Error "Connection failed"
- Pastikan MySQL service berjalan
- Cek username/password di config.php
- Pastikan port MySQL tidak terblokir firewall

### Error "Access denied"
- Pastikan user MySQL memiliki hak CREATE DATABASE
- Coba gunakan user dengan privilege penuh

### Error "File not found"
- Pastikan path file benar di htdocs/www
- Cek permission folder (755 untuk folder, 644 untuk file)

### Error Upload Foto
- Pastikan folder `uploads/` dapat ditulis (permission 777)
- Cek setting `upload_max_filesize` di php.ini

## 📁 Struktur File Setelah Instalasi
```
PWEB-SQL/
├── index.html              # Halaman utama
├── daftar.html            # Form pendaftaran
├── data-siswa.html        # Lihat data siswa
├── admin.html             # Panel admin
├── css/
│   └── style.css          # Styling
├── js/
│   ├── script.js          # JavaScript utama
│   ├── data-siswa.js      # JS untuk data siswa
│   └── admin.js           # JS untuk admin
├── php/
│   ├── config.php         # Konfigurasi database
│   ├── install_fixed.php  # Installer database (gunakan ini!)
│   ├── proses_daftar.php  # Proses pendaftaran
│   └── [file PHP lainnya] # API endpoints
├── uploads/               # Folder upload foto
└── sample_data.sql        # Data contoh (opsional)
```

## 🌟 Fitur Aplikasi
- ✅ Pendaftaran siswa online
- ✅ Upload foto siswa
- ✅ Panel admin lengkap
- ✅ Statistik dan laporan
- ✅ Validasi data real-time
- ✅ Responsive design
- ✅ Security headers
- ✅ Data export/import

## 📞 Support
Jika mengalami masalah:
1. Cek error log di browser console (F12)
2. Cek error log Apache/MySQL di XAMPP Control Panel
3. Pastikan semua file ter-copy dengan benar
4. Gunakan `test_install.php` untuk debug koneksi

---
**🎓 SMA Negeri 1 - Sistem Pendaftaran Siswa Baru**
