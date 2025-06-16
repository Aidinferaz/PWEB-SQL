<?php
// Simple Database Installer - Debug Version
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Database Installer</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='bg-light'><div class='container mt-3'>";

try {
    echo "<div class='card'><div class='card-header'><h3>Database Installation Progress</h3></div><div class='card-body'>";
    
    // Step 1: Connect to MySQL
    echo "<div class='alert alert-info'>Step 1: Connecting to MySQL...</div>";
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='alert alert-success'>✅ MySQL connection successful</div>";
    
    // Step 2: Create database
    echo "<div class='alert alert-info'>Step 2: Creating database...</div>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS pendaftaran_siswa");
    $pdo->exec("USE pendaftaran_siswa");
    echo "<div class='alert alert-success'>✅ Database created/selected</div>";
    
    // Step 3: Create siswa table
    echo "<div class='alert alert-info'>Step 3: Creating siswa table...</div>";
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
    $pdo->exec($createSiswaTable);
    echo "<div class='alert alert-success'>✅ Siswa table created</div>";
    
    // Step 4: Create admin table
    echo "<div class='alert alert-info'>Step 4: Creating admin table...</div>";
    $createAdminTable = "
        CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $pdo->exec($createAdminTable);
    echo "<div class='alert alert-success'>✅ Admin table created</div>";
    
    // Step 5: Insert admin
    echo "<div class='alert alert-info'>Step 5: Creating admin user...</div>";
    $checkAdmin = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
    $checkAdmin->execute(['admin']);
    
    if ($checkAdmin->fetchColumn() == 0) {
        $insertAdmin = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $insertAdmin->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);
        echo "<div class='alert alert-success'>✅ Admin user created (username: admin, password: admin123)</div>";
    } else {
        echo "<div class='alert alert-warning'>⚠️ Admin user already exists</div>";
    }
    
    // Step 6: Create settings table
    echo "<div class='alert alert-info'>Step 6: Creating settings table...</div>";
    $createSettingsTable = "
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ";
    $pdo->exec($createSettingsTable);
    echo "<div class='alert alert-success'>✅ Settings table created</div>";
    
    // Step 7: Insert default settings
    echo "<div class='alert alert-info'>Step 7: Inserting default settings...</div>";
    $settings = [
        ['status_pendaftaran', '1'],
        ['batas_nilai', '75'],
        ['kapasitas_ipa', '108'],
        ['kapasitas_ips', '72'],
        ['kapasitas_bahasa', '36']
    ];
    
    foreach ($settings as $setting) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $check->execute([$setting[0]]);
        
        if ($check->fetchColumn() == 0) {
            $insert = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $insert->execute([$setting[0], $setting[1]]);
        }
    }
    echo "<div class='alert alert-success'>✅ Default settings inserted</div>";
    
    // Success message
    echo "<div class='alert alert-success mt-4'>";
    echo "<h4>🎉 Installation Completed Successfully!</h4>";
    echo "<p><strong>Database:</strong> pendaftaran_siswa</p>";
    echo "<p><strong>Admin Login:</strong></p>";
    echo "<ul><li>Username: admin</li><li>Password: admin123</li></ul>";
    echo "</div>";
    
    echo "<div class='d-grid gap-2'>";
    echo "<a href='../index.html' class='btn btn-primary btn-lg'>Go to Home Page</a>";
    echo "<a href='../admin.html' class='btn btn-success btn-lg'>Login to Admin</a>";
    echo "</div>";
    
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Database Error:</h4>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
    
    echo "<div class='alert alert-info'>";
    echo "<h5>Troubleshooting:</h5>";
    echo "<ul>";
    echo "<li>Make sure XAMPP/WAMP is running</li>";
    echo "<li>Check MySQL service is active</li>";
    echo "<li>Verify database credentials</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<button onclick='window.location.reload()' class='btn btn-warning'>Try Again</button>";
} catch(Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ General Error:</h4>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</div></div></div></body></html>";
?>
