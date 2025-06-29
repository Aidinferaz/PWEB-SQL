<?php
require_once 'config.php';

try {
    // First, connect without specifying database to create it
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    // Connect to MySQL server (not specific database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS pendaftaran_siswa");
    $pdo->exec("USE pendaftaran_siswa");
    
    // Now use the Database class
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Failed to connect to database");
    }
    
    // Create siswa table
    $createSiswaTable = "
        CREATE TABLE IF NOT EXISTS siswa (
            id INT AUTO_INCREMENT PRIMARY KEY,
            no_pendaftaran VARCHAR(20) UNIQUE NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
            tempat_lahir VARCHAR(50) NOT NULL,
            tanggal_lahir DATE NOT NULL,
            agama VARCHAR(20) NOT NULL,
            nik VARCHAR(16) UNIQUE NOT NULL,
            alamat TEXT NOT NULL,
            no_hp VARCHAR(15) NOT NULL,
            email VARCHAR(100),
            nama_ayah VARCHAR(100) NOT NULL,
            nama_ibu VARCHAR(100) NOT NULL,
            pekerjaan_ayah VARCHAR(50),
            pekerjaan_ibu VARCHAR(50),
            no_hp_ortu VARCHAR(15) NOT NULL,
            asal_sekolah VARCHAR(100) NOT NULL,
            tahun_lulus YEAR NOT NULL,
            nilai_rata_rata DECIMAL(5,2) NOT NULL,
            jurusan_pilihan ENUM('IPA', 'IPS', 'Bahasa') NOT NULL,
            foto VARCHAR(255),
            status ENUM('Pending', 'Diterima', 'Ditolak') DEFAULT 'Pending',
            keterangan TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    
    $db->exec($createSiswaTable);
    
    // Create admin table
    $createAdminTable = "
        CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
      $db->exec($createAdminTable);
    
    // Check if admin already exists
    $checkAdmin = $db->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
    $checkAdmin->execute(['admin']);
    
    $adminExists = false;
    if ($checkAdmin->fetchColumn() == 0) {
        $insertAdmin = $db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $insertAdmin->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);
    } else {
        $adminExists = true;
    }
    
    // Create settings table
    $createSettingsTable = "
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    
    $db->exec($createSettingsTable);
      // Insert default settings
    $defaultSettings = [
        ['setting_key' => 'status_pendaftaran', 'setting_value' => '1'],
        ['setting_key' => 'batas_nilai', 'setting_value' => '75'],
        ['setting_key' => 'kapasitas_ipa', 'setting_value' => '108'],
        ['setting_key' => 'kapasitas_ips', 'setting_value' => '72'],
        ['setting_key' => 'kapasitas_bahasa', 'setting_value' => '36']
    ];
    
    foreach ($defaultSettings as $setting) {
        $checkSetting = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $checkSetting->execute([$setting['setting_key']]);
        
        if ($checkSetting->fetchColumn() == 0) {
            $insertSetting = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $insertSetting->execute([$setting['setting_key'], $setting['setting_value']]);
        }
    }
      
    echo "<!DOCTYPE html>";
    echo "<html lang='id'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Instalasi Berhasil - SMA Negeri 1</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
    echo "</head>";
    echo "<body class='bg-light'>";
    echo "<div class='container mt-5'>";
    echo "<div class='row justify-content-center'>";
    echo "<div class='col-md-8'>";
    echo "<div class='card shadow'>";
    echo "<div class='card-header bg-success text-white text-center'>";
    echo "<h3><i class='fas fa-check-circle me-2'></i>Instalasi Berhasil!</h3>";
    echo "</div>";
    echo "<div class='card-body'>";    echo "<div class='alert alert-success'>";
    echo "<h5><i class='fas fa-database me-2'></i>Database berhasil dibuat!</h5>";
    echo "<p>Database <strong>pendaftaran_siswa</strong> dan semua tabel telah berhasil dibuat.</p>";
    echo "</div>";
    
    echo "<div class='alert alert-info'>";
    echo "<h5><i class='fas fa-user-shield me-2'></i>Admin Default:</h5>";
    if ($adminExists) {
        echo "<p class='text-warning'><i class='fas fa-info-circle me-1'></i>Admin sudah ada sebelumnya.</p>";
        echo "<p><strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> Gunakan password yang sudah ada</p>";
    } else {
        echo "<p><strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> admin123</p>";
        echo "<p class='text-warning'><i class='fas fa-exclamation-triangle me-1'></i>Segera ubah password setelah login pertama!</p>";
    }
    echo "</div>";
    echo "<div class='d-grid gap-2'>";
    echo "<a href='../index.html' class='btn btn-primary btn-lg'>";
    echo "<i class='fas fa-home me-2'></i>Ke Halaman Utama";
    echo "</a>";
    echo "<a href='../admin.html' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-user-cog me-2'></i>Login Admin";
    echo "</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    
} catch(PDOException $exception) {
    echo "<!DOCTYPE html>";
    echo "<html lang='id'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Error Instalasi - SMA Negeri 1</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
    echo "</head>";
    echo "<body class='bg-light'>";
    echo "<div class='container mt-5'>";
    echo "<div class='row justify-content-center'>";
    echo "<div class='col-md-8'>";
    echo "<div class='card shadow'>";
    echo "<div class='card-header bg-danger text-white text-center'>";
    echo "<h3><i class='fas fa-exclamation-triangle me-2'></i>Error Instalasi!</h3>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='alert alert-danger'>";
    echo "<h5><i class='fas fa-database me-2'></i>Database Error:</h5>";
    echo "<p>" . $exception->getMessage() . "</p>";
    echo "</div>";
    echo "<div class='alert alert-info'>";
    echo "<h5><i class='fas fa-tools me-2'></i>Troubleshooting:</h5>";
    echo "<ul>";
    echo "<li>Pastikan MySQL service sudah berjalan</li>";
    echo "<li>Cek username dan password database di config.php</li>";
    echo "<li>Pastikan user memiliki hak akses CREATE DATABASE</li>";
    echo "</ul>";
    echo "</div>";
    echo "<div class='d-grid'>";
    echo "<button onclick='window.location.reload()' class='btn btn-warning'>";
    echo "<i class='fas fa-redo me-2'></i>Coba Lagi";
    echo "</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
} catch(Exception $exception) {
    echo "<!DOCTYPE html>";
    echo "<html lang='id'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Error Instalasi - SMA Negeri 1</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
    echo "</head>";
    echo "<body class='bg-light'>";
    echo "<div class='container mt-5'>";
    echo "<div class='row justify-content-center'>";
    echo "<div class='col-md-8'>";
    echo "<div class='card shadow'>";
    echo "<div class='card-header bg-danger text-white text-center'>";
    echo "<h3><i class='fas fa-exclamation-triangle me-2'></i>Error Instalasi!</h3>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='alert alert-danger'>";
    echo "<h5><i class='fas fa-bug me-2'></i>General Error:</h5>";
    echo "<p>" . $exception->getMessage() . "</p>";
    echo "</div>";
    echo "<div class='d-grid'>";
    echo "<button onclick='window.location.reload()' class='btn btn-warning'>";
    echo "<i class='fas fa-redo me-2'></i>Coba Lagi";
    echo "</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
}
?>
