<?php
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        sendResponse(false, 'Koneksi database gagal');
    }
    
    // Get statistics
    $totalQuery = "SELECT COUNT(*) as total FROM siswa";
    $totalStmt = $db->prepare($totalQuery);
    $totalStmt->execute();
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $pendingQuery = "SELECT COUNT(*) as total FROM siswa WHERE status = 'Pending'";
    $pendingStmt = $db->prepare($pendingQuery);
    $pendingStmt->execute();
    $pending = $pendingStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $diterimaQuery = "SELECT COUNT(*) as total FROM siswa WHERE status = 'Diterima'";
    $diterimaStmt = $db->prepare($diterimaQuery);
    $diterimaStmt->execute();
    $diterima = $diterimaStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $ditolakQuery = "SELECT COUNT(*) as total FROM siswa WHERE status = 'Ditolak'";
    $ditolakStmt = $db->prepare($ditolakQuery);
    $ditolakStmt->execute();
    $ditolak = $ditolakStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    sendResponse(true, '', [
        'total_siswa' => (int)$total,
        'total_pending' => (int)$pending,
        'total_diterima' => (int)$diterima,
        'total_ditolak' => (int)$ditolak
    ]);
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
