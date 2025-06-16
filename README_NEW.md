# 🎓 Aplikasi Pendaftaran Siswa Baru - SMA Negeri 1

Aplikasi web modern untuk pendaftaran siswa baru dengan fitur lengkap dan antarmuka yang user-friendly.

## 🌟 Fitur Utama

### 👨‍🎓 Untuk Siswa
- **Pendaftaran Online** dengan form yang mudah digunakan
- **Upload Foto** siswa dengan validasi format dan ukuran
- **Validasi Real-time** untuk data yang diinput
- **Nomor Pendaftaran Otomatis** yang unik
- **Responsive Design** untuk semua device

### 👨‍💼 Untuk Admin
- **Dashboard Admin** dengan statistik lengkap
- **Manajemen Data Siswa** (view, edit, hapus)
- **Update Status** pendaftaran (Pending/Diterima/Ditolak)
- **Laporan dan Statistik** per jurusan
- **Data Export** dalam format yang mudah dibaca

### 🔧 Fitur Teknis
- **Security Headers** untuk proteksi keamanan
- **File Upload Protection** dengan validasi ketat
- **Database Optimization** dengan indexing yang tepat
- **Error Handling** yang komprehensif
- **Backup & Restore** data

## 🚀 Quick Start

### 1. Download & Extract
```bash
# Extract ke htdocs (XAMPP) atau www (WAMP)
C:\xampp\htdocs\PWEB-SQL\
```

### 2. Instalasi Database
**Akses:** `http://localhost/PWEB-SQL/php/install_fixed.php`

✅ Script akan otomatis setup database dan tabel yang diperlukan

### 3. Login Admin
- **URL:** `http://localhost/PWEB-SQL/admin.html`
- **Username:** admin
- **Password:** admin123

### 4. Mulai Gunakan!
- **Halaman Utama:** `http://localhost/PWEB-SQL/`
- **Form Pendaftaran:** `http://localhost/PWEB-SQL/daftar.html`

## 📋 Requirements

- **Web Server:** Apache (XAMPP/WAMP/LAMP)
- **Database:** MySQL 5.7+ atau MariaDB
- **PHP:** Version 7.4+ atau 8.x
- **Browser:** Chrome, Firefox, Safari, Edge (modern browsers)

## 🔧 Konfigurasi

### Database Settings
Edit `php/config.php` jika diperlukan:
```php
private $host = 'localhost';
private $db_name = 'pendaftaran_siswa';
private $username = 'root';
private $password = '';
```

### Upload Settings
- **Max File Size:** 2MB
- **Allowed Formats:** JPG, JPEG, PNG
- **Upload Folder:** `uploads/` (auto-created)

## 🗂️ Struktur Database

### Tabel `siswa`
Data lengkap pendaftar termasuk biodata, orang tua, dan akademik

### Tabel `admin`
Manajemen user admin dengan password ter-enkripsi

### Tabel `settings`
Konfigurasi aplikasi (status pendaftaran, kapasitas, dll)

## 🎨 Technology Stack

- **Frontend:** HTML5, CSS3 (Bootstrap 5), JavaScript (Vanilla)
- **Backend:** PHP 8.x dengan PDO
- **Database:** MySQL/MariaDB
- **Icons:** Font Awesome 6
- **Styling:** Bootstrap 5.3 + Custom CSS

## 📱 Screenshots

### Halaman Utama
Interface modern dengan informasi sekolah dan akses cepat pendaftaran

### Form Pendaftaran
Form multi-step dengan validasi real-time dan upload foto

### Dashboard Admin
Panel kontrol lengkap dengan statistik dan manajemen data

## 🔒 Security Features

- **Password Hashing** dengan PHP password_hash()
- **Prepared Statements** untuk mencegah SQL Injection
- **File Upload Validation** untuk keamanan server
- **CSRF Protection** pada form-form penting
- **.htaccess Security** headers dan access control

## 📊 Performance

- **Optimized Queries** dengan indexing yang tepat
- **Lazy Loading** untuk gambar dan konten besar
- **Minified Assets** untuk loading yang cepat
- **Caching Strategy** untuk performa optimal

## 🛠️ Development

### Local Development
```bash
# Clone/download project
# Setup XAMPP/WAMP
# Run install_fixed.php
# Start coding!
```

### File Structure
```
PWEB-SQL/
├── 📄 index.html           # Landing page
├── 📄 daftar.html         # Registration form
├── 📄 data-siswa.html     # Student data view
├── 📄 admin.html          # Admin panel
├── 📁 css/                # Stylesheets
├── 📁 js/                 # JavaScript files
├── 📁 php/                # Backend logic
├── 📁 uploads/            # File uploads
└── 📄 README.md           # This file
```

## 🤝 Contributing

1. Fork the project
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

- 📧 Email: support@smansatu.sch.id
- 📱 WhatsApp: +62 xxx-xxxx-xxxx
- 🌐 Website: https://smansatu.sch.id

## 🙏 Acknowledgments

- Bootstrap Team untuk framework CSS yang amazing
- Font Awesome untuk icon yang comprehensive
- PHP Community untuk dokumentasi yang excellent

---

**Made with ❤️ for SMA Negeri 1**

> 💡 **Tip:** Gunakan `INSTALL_GUIDE.md` untuk panduan instalasi detail step-by-step!
