<?php
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        sendResponse(false, 'Koneksi database gagal');
    }
    
    // Get data for charts
    
    // Data per jurusan
    $jurusanQuery = "
        SELECT 
            jurusan_pilihan, 
            COUNT(*) as jumlah 
        FROM siswa 
        GROUP BY jurusan_pilihan
    ";
    
    $jurusanStmt = $db->prepare($jurusanQuery);
    $jurusanStmt->execute();
    $jurusanData = $jurusanStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $jurusan = [];
    foreach ($jurusanData as $row) {
        $jurusan[$row['jurusan_pilihan']] = (int)$row['jumlah'];
    }
    
    // Data per status
    $statusQuery = "
        SELECT 
            status, 
            COUNT(*) as jumlah 
        FROM siswa 
        GROUP BY status
    ";
    
    $statusStmt = $db->prepare($statusQuery);
    $statusStmt->execute();
    $statusData = $statusStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $status = [];
    foreach ($statusData as $row) {
        $status[$row['status']] = (int)$row['jumlah'];
    }
    
    sendResponse(true, '', [
        'jurusan' => $jurusan,
        'status' => $status
    ]);
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
