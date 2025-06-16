<?php
// Test Database Connection
echo "<!DOCTYPE html>";
echo "<html lang='id'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Test Koneksi Database</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body class='bg-light'>";
echo "<div class='container mt-5'>";
echo "<div class='card'>";
echo "<div class='card-header'>";
echo "<h3>Test Koneksi Database</h3>";
echo "</div>";
echo "<div class='card-body'>";

try {
    // Test MySQL connection
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    echo "<div class='alert alert-info'>Mencoba koneksi ke MySQL server...</div>";
    
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='alert alert-success'>✅ Koneksi ke MySQL server berhasil!</div>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'pendaftaran_siswa'");
    $dbExists = $stmt->rowCount() > 0;
    
    if ($dbExists) {
        echo "<div class='alert alert-success'>✅ Database 'pendaftaran_siswa' sudah ada</div>";
    } else {
        echo "<div class='alert alert-warning'>⚠️ Database 'pendaftaran_siswa' belum ada</div>";
    }
    
    // Show MySQL version
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "<div class='alert alert-info'>MySQL Version: $version</div>";
    
    // Show PHP version
    echo "<div class='alert alert-info'>PHP Version: " . phpversion() . "</div>";
    
    echo "<div class='d-grid gap-2'>";
    echo "<a href='install.php' class='btn btn-primary'>Lanjut ke Instalasi</a>";
    echo "<a href='../index.html' class='btn btn-secondary'>Kembali ke Home</a>";
    echo "</div>";
    
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>❌ Error koneksi: " . $e->getMessage() . "</div>";
    echo "<div class='alert alert-info'>";
    echo "<h5>Troubleshooting:</h5>";
    echo "<ul>";
    echo "<li>Pastikan XAMPP/WAMP sudah running</li>";
    echo "<li>Pastikan MySQL service aktif</li>";
    echo "<li>Cek username dan password database</li>";
    echo "</ul>";
    echo "</div>";
    echo "<button onclick='window.location.reload()' class='btn btn-warning'>Coba Lagi</button>";
}

echo "</div>";
echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";
?>
