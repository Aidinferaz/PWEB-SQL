<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    sendResponse(false, 'ID siswa tidak valid');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        sendResponse(false, 'Koneksi database gagal');
    }
    
    $id = (int)$_GET['id'];
    
    // Get siswa detail
    $query = "
        SELECT 
            id, no_pendaftaran, nama_lengkap, jenis_kelamin, tempat_lahir, 
            tanggal_lahir, agama, nik, alamat, no_hp, email, nama_ayah, 
            nama_ibu, pekerjaan_ayah, pekerjaan_ibu, no_hp_ortu, asal_sekolah, 
            tahun_lulus, nilai_rata_rata, jurusan_pilihan, foto, status, 
            keterangan, created_at, updated_at
        FROM siswa 
        WHERE id = :id
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['id' => $id]);
    
    $siswaData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$siswaData) {
        sendResponse(false, 'Data siswa tidak ditemukan');
    }
    
    sendResponse(true, '', $siswaData);
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga');
}
?>
