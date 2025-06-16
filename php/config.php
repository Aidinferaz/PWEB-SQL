<?php
// Database Configuration
class Database {
    private $host = 'localhost';
    private $db_name = 'pendaftaran_siswa';
    private $username = 'root';    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        try {
            // Try to connect to specific database first
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // If database doesn't exist, try to connect to MySQL server only
            try {
                $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Create database if it doesn't exist
                $this->conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name);
                $this->conn->exec("USE " . $this->db_name);
            } catch(PDOException $e) {
                echo "Connection error: " . $e->getMessage();
                return null;
            }
        }
        return $this->conn;
    }
}

// Response helper function
function sendResponse($success, $message = '', $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// File upload helper function
function uploadFile($file, $uploadDir = '../uploads/') {
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (maksimal 2MB)'];
    }
    
    $fileName = time() . '_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $fileName];
    }
    
    return ['success' => false, 'message' => 'Gagal mengupload file'];
}

// Generate nomor pendaftaran
function generateNoPendaftaran() {
    $year = date('Y');
    $month = date('m');
    $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    return "PSB{$year}{$month}{$random}";
}

// Validation functions
function validateRequired($data, $fields) {
    $errors = [];
    foreach ($fields as $field) {
        if (empty($data[$field])) {
            $errors[] = "Field {$field} wajib diisi";
        }
    }
    return $errors;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateNIK($nik) {
    return preg_match('/^\d{16}$/', $nik);
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
