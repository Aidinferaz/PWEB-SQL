# Aplikasi Web Pendaftaran Siswa Baru
## SMA Negeri 1

Aplikasi web untuk mengelola pendaftaran siswa baru dengan implementasi MySQL. Aplikasi ini dibuat menggunakan HTML, CSS, JavaScript, PHP, dan MySQL.

## 🚀 Fitur Utama

### 1. **Halaman Publik**
- **Beranda**: Informasi sekolah, statistik, dan panduan pendaftaran
- **Formulir Pendaftaran**: Form lengkap untuk calon siswa baru
- **Data Siswa**: Daftar calon siswa yang telah mendaftar
- **Interface responsif** dengan desain modern

### 2. **Panel Admin**
- **Dashboard**: Statistik dan ringkasan data
- **Kelola Siswa**: CRUD (Create, Read, Update, Delete) data siswa
- **Update Status**: Ubah status pendaftaran (Pending/Diterima/Ditolak)
- **Laporan**: Grafik dan analisis data pendaftaran
- **Export Data**: Export ke Excel dan PDF
- **Pengaturan Sistem**: Konfigurasi aplikasi

### 3. **Fitur Database**
- **Struktur tabel yang normalized**
- **Validasi data lengkap**
- **Upload dan manajemen file foto**
- **Generate nomor pendaftaran otomatis**
- **Backup dan restore database**

## 🛠️ Teknologi yang Digunakan

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Framework CSS**: Bootstrap 5.3
- **Icons**: Font Awesome 6.0
- **Charts**: Chart.js
- **DataTables**: jQuery DataTables
- **Authentication**: Session-based

## 📋 Persyaratan Sistem

- **Web Server**: Apache/Nginx
- **PHP**: Versi 7.4 atau lebih baru
- **MySQL**: Versi 8.0 atau lebih baru
- **Browser**: Chrome, Firefox, Safari, Edge (versi terbaru)

## ⚙️ Instalasi

### 1. **Persiapan Environment**
```bash
# Pastikan XAMPP/WAMP/MAMP sudah terinstall
# Start Apache dan MySQL
```

### 2. **Clone/Download Project**
```bash
# Download atau copy project ke folder htdocs (untuk XAMPP)
# Lokasi: C:/xampp/htdocs/pendaftaran-siswa/
```

### 3. **Konfigurasi Database**
1. Buka `http://localhost/phpmyadmin`
2. Buat database baru: `pendaftaran_siswa`
3. Atau jalankan installer otomatis:
   ```
   http://localhost/pendaftaran-siswa/php/install.php
   ```

### 4. **Konfigurasi Koneksi**
Edit file `php/config.php` jika perlu:
```php
private $host = 'localhost';
private $db_name = 'pendaftaran_siswa';
private $username = 'root';
private $password = '';
```

### 5. **Set Permissions**
Pastikan folder `uploads/` memiliki permission write:
```bash
chmod 755 uploads/
```

## 🔐 Login Admin

**Default Admin Account:**
- Username: `admin`
- Password: `admin123`

*Segera ubah password setelah login pertama*

## 📊 Struktur Database

### Tabel `siswa`
```sql
- id (Primary Key)
- no_pendaftaran (Unique)
- nama_lengkap
- jenis_kelamin
- tempat_lahir, tanggal_lahir
- agama, nik
- alamat, no_hp, email
- nama_ayah, nama_ibu
- pekerjaan_ayah, pekerjaan_ibu
- no_hp_ortu
- asal_sekolah, tahun_lulus
- nilai_rata_rata, jurusan_pilihan
- foto, status, keterangan
- created_at, updated_at
```

### Tabel `admin`
```sql
- id (Primary Key)
- username (Unique)
- password (Hashed)
- created_at
```

### Tabel `settings`
```sql
- id (Primary Key)
- setting_key (Unique)
- setting_value
- created_at, updated_at
```

## 🎯 Cara Penggunaan

### **Untuk Calon Siswa:**
1. Kunjungi halaman utama aplikasi
2. Klik "Daftar Sekarang"
3. Isi formulir pendaftaran lengkap
4. Upload pas foto (JPG/PNG, max 2MB)
5. Submit form dan simpan nomor pendaftaran

### **Untuk Admin:**
1. Login di `/admin.html`
2. **Dashboard**: Lihat statistik terkini
3. **Kelola Siswa**: 
   - Lihat daftar pendaftar
   - Update status pendaftaran
   - Hapus data jika diperlukan
4. **Laporan**: Analisis data dengan grafik
5. **Pengaturan**: Konfigurasi sistem

## 📂 Struktur Project

```
pendaftaran-siswa/
├── css/
│   └── style.css              # Styling custom
├── js/
│   ├── script.js              # JavaScript utama
│   ├── data-siswa.js          # JavaScript halaman data siswa
│   └── admin.js               # JavaScript panel admin
├── php/
│   ├── config.php             # Konfigurasi database
│   ├── install.php            # Installer database
│   ├── proses_daftar.php      # Proses pendaftaran
│   ├── get_statistics.php     # API statistik
│   ├── get_siswa.php          # API data siswa
│   ├── get_siswa_detail.php   # API detail siswa
│   ├── delete_siswa.php       # API hapus siswa
│   ├── update_status.php      # API update status
│   ├── get_recent_applications.php # API aplikasi terbaru
│   └── get_laporan.php        # API data laporan
├── uploads/                   # Folder foto upload
├── index.html                 # Halaman utama
├── daftar.html               # Halaman pendaftaran
├── data-siswa.html           # Halaman data siswa
├── admin.html                # Panel admin
└── README.md                 # Dokumentasi
```

## 🔧 Kustomisasi

### **Mengubah Tema Warna:**
Edit file `css/style.css`:
```css
:root {
    --primary-color: #0d6efd;    /* Biru */
    --success-color: #198754;    /* Hijau */
    --warning-color: #ffc107;    /* Kuning */
    --danger-color: #dc3545;     /* Merah */
}
```

### **Menambah Field Baru:**
1. Alter tabel database
2. Update form HTML
3. Update proses PHP
4. Update tampilan data

### **Mengubah Validasi:**
Edit file `php/config.php` dan `js/script.js`

## 🚨 Troubleshooting

### **Error Koneksi Database:**
- Periksa service MySQL sudah berjalan
- Cek konfigurasi di `config.php`
- Pastikan database `pendaftaran_siswa` sudah dibuat

### **Upload File Gagal:**
- Periksa permission folder `uploads/`
- Cek ukuran file (max 2MB)
- Pastikan format file JPG/PNG

### **Admin Tidak Bisa Login:**
- Gunakan username: `admin`, password: `admin123`
- Periksa tabel `admin` di database
- Jalankan ulang `install.php`

## 📋 To-Do / Pengembangan

- [ ] Sistem notifikasi email
- [ ] SMS gateway untuk konfirmasi
- [ ] Payment gateway untuk biaya pendaftaran
- [ ] API RESTful
- [ ] PWA (Progressive Web App)
- [ ] Real-time notifications
- [ ] Advanced reporting
- [ ] Batch operations
- [ ] Role-based access control
**Dibuat dengan ❤️ untuk pendidikan yang lebih baik**

*SMA Negeri 1 - Excellence in Education*
