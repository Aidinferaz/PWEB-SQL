# Folder Uploads

Folder ini digunakan untuk menyimpan foto-foto yang diupload oleh calon siswa.

## Keamanan
- Hanya file gambar (JPG, JPEG, PNG) yang diizinkan
- Maksimal ukuran file: 2MB
- Nama file di-rename otomatis untuk keamanan

## Struktur
```
uploads/
├── .htaccess              # Keamanan folder
├── README.md              # File ini
└── [foto-files]           # File foto yang diupload
```

**PENTING**: Pastikan folder ini memiliki permission write (755 atau 777) agar upload dapat berfungsi.
