# 🚀 Panduan Instalasi Aplikasi Pendaftaran Siswa Baru

## Langkah 1: Persiapan Environment

### A. Install XAMPP (Recommended)
1. Download XAMPP dari [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install XAMPP di komputer Anda
3. Start Apache dan MySQL dari XAMPP Control Panel

### B. Alternatif: WAMP/MAMP
- **WAMP** (Windows): [http://www.wampserver.com](http://www.wampserver.com)
- **MAMP** (Mac): [https://www.mamp.info](https://www.mamp.info)

## Langkah 2: Setup Project

### A. Copy Project ke Web Server
```bash
# Untuk XAMPP
Copy folder project ke: C:/xampp/htdocs/pendaftaran-siswa/

# Untuk WAMP
Copy folder project ke: C:/wamp64/www/pendaftaran-siswa/
```

### B. Set Permissions (Linux/Mac)
```bash
chmod 755 uploads/
chmod 644 *.html *.php *.css *.js
```

## Langkah 3: Konfigurasi Database

### Opsi A: Instalasi Otomatis (Recommended)
1. Buka browser
2. Kunjungi: `http://localhost/pendaftaran-siswa/php/install.php`
3. Database dan tabel akan dibuat otomatis
4. Admin default akan dibuat

### Opsi B: Manual Setup
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Buat database baru: `pendaftaran_siswa`
3. Import file SQL atau jalankan script dari `install.php`

## Langkah 4: Testing

### A. Test Halaman Utama
- Kunjungi: `http://localhost/pendaftaran-siswa/`
- Pastikan halaman loading dengan baik

### B. Test Pendaftaran
1. Klik "Daftar Sekarang"
2. Isi form pendaftaran
3. Upload foto (optional)
4. Submit form

### C. Test Admin Panel
1. Kunjungi: `http://localhost/pendaftaran-siswa/admin.html`
2. Login dengan:
   - Username: `admin`
   - Password: `admin123`

## Langkah 5: Konfigurasi Lanjutan

### A. Update Database Connection (Jika Perlu)
Edit file `php/config.php`:
```php
private $host = 'localhost';     // Database host
private $db_name = 'pendaftaran_siswa';  // Database name
private $username = 'root';      // Database username
private $password = '';          // Database password
```

### B. Ubah Password Admin
1. Login ke admin panel
2. Pergi ke Pengaturan
3. Ubah password default

### C. Load Sample Data (Optional)
```sql
-- Di phpMyAdmin, jalankan script dari file:
source sample_data.sql;
```

## 🔧 Troubleshooting

### Problem: "Database connection failed"
**Solusi:**
1. Pastikan MySQL service berjalan
2. Cek konfigurasi di `config.php`
3. Pastikan database `pendaftaran_siswa` sudah dibuat

### Problem: "Upload file failed"
**Solusi:**
1. Cek permission folder `uploads/` (harus writable)
2. Pastikan file format JPG/PNG
3. Pastikan ukuran file < 2MB

### Problem: "Page not found"
**Solusi:**
1. Pastikan project di folder yang benar
2. Cek URL: `http://localhost/pendaftaran-siswa/`
3. Restart Apache service

### Problem: Admin tidak bisa login
**Solusi:**
1. Jalankan ulang `install.php`
2. Atau manual insert admin:
```sql
INSERT INTO admin (username, password) 
VALUES ('admin', '$2y$10$...hash...');
```

## 📱 Test di Mobile
1. Cari IP komputer: `ipconfig` (Windows) atau `ifconfig` (Linux/Mac)
2. Akses dari HP: `http://[IP_ADDRESS]/pendaftaran-siswa/`
3. Contoh: `http://192.168.1.100/pendaftaran-siswa/`

## 🚀 Go Live (Production)

### A. Upload ke Hosting
1. Upload semua file ke hosting
2. Buat database di cPanel/Hosting Panel
3. Update `config.php` dengan data hosting
4. Set permissions folder uploads

### B. SSL Certificate
1. Install SSL certificate
2. Update URL ke https://
3. Force HTTPS redirect di `.htaccess`

## 📞 Support

Jika mengalami masalah:
1. Cek log error di `error_log` atau console browser
2. Pastikan semua requirement terpenuhi
3. Cek dokumentasi README.md

---

**Selamat! Aplikasi Pendaftaran Siswa Baru siap digunakan! 🎉**
