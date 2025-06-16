<?php
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        sendResponse(false, 'Koneksi database gagal');
    }
    
    // Get all siswa data
    $query = "
        SELECT 
            id, no_pendaftaran, nama_lengkap, jenis_kelamin, tempat_lahir, 
            tanggal_lahir, agama, nik, alamat, no_hp, email, nama_ayah, 
            nama_ibu, pekerjaan_ayah, pekerjaan_ibu, no_hp_ortu, asal_sekolah, 
            tahun_lulus, nilai_rata_rata, jurusan_pilihan, foto, status, 
            keterangan, created_at, updated_at
        FROM siswa 
        ORDER BY created_at DESC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $siswaData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(true, '', $siswaData);
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
