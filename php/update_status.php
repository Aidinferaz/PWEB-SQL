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
    
    if (!isset($input['status']) || empty($input['status'])) {
        sendResponse(false, 'Status tidak valid');
    }
    
    $id = (int)$input['id'];
    $status = sanitizeInput($input['status']);
    $keterangan = isset($input['keterangan']) ? sanitizeInput($input['keterangan']) : null;
    
    // Validate status
    $validStatus = ['Pending', 'Diterima', 'Ditolak'];
    if (!in_array($status, $validStatus)) {
        sendResponse(false, 'Status tidak valid');
    }
    
    // Check if siswa exists
    $checkQuery = "SELECT id FROM siswa WHERE id = :id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute(['id' => $id]);
    
    if (!$checkStmt->fetchColumn()) {
        sendResponse(false, 'Data siswa tidak ditemukan');
    }
    
    // Update status
    $updateQuery = "UPDATE siswa SET status = :status, keterangan = :keterangan WHERE id = :id";
    $updateStmt = $db->prepare($updateQuery);
    
    $updateData = [
        'id' => $id,
        'status' => $status,
        'keterangan' => $keterangan
    ];
    
    if ($updateStmt->execute($updateData)) {
        sendResponse(true, 'Status berhasil diperbarui');
    } else {
        sendResponse(false, 'Gagal memperbarui status');
    }
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
