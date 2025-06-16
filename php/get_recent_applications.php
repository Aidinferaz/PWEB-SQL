<?php
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        sendResponse(false, 'Koneksi database gagal');
    }
    
    // Get recent applications (last 10)
    $query = "
        SELECT 
            id, no_pendaftaran, nama_lengkap, jurusan_pilihan, status, created_at
        FROM siswa 
        ORDER BY created_at DESC 
        LIMIT 10
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $recentApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(true, '', $recentApplications);
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
