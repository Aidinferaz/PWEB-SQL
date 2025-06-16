<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        sendResponse(false, 'Koneksi database gagal');
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id']) || empty($input['id'])) {
        sendResponse(false, 'ID siswa tidak valid');
    }
    
    $id = (int)$input['id'];
    
    // Check if siswa exists
    $checkQuery = "SELECT id, foto FROM siswa WHERE id = :id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute(['id' => $id]);
    $siswa = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$siswa) {
        sendResponse(false, 'Data siswa tidak ditemukan');
    }
    
    // Delete foto file if exists
    if ($siswa['foto']) {
        $fotoPath = '../uploads/' . $siswa['foto'];
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
    }
    
    // Delete siswa data
    $deleteQuery = "DELETE FROM siswa WHERE id = :id";
    $deleteStmt = $db->prepare($deleteQuery);
    
    if ($deleteStmt->execute(['id' => $id])) {
        sendResponse(true, 'Data siswa berhasil dihapus');
    } else {
        sendResponse(false, 'Gagal menghapus data siswa');
    }
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
