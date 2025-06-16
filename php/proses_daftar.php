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
    
    // Validate required fields
    $requiredFields = [
        'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'agama', 'nik', 'alamat', 'no_hp', 'nama_ayah', 'nama_ibu',
        'no_hp_ortu', 'asal_sekolah', 'tahun_lulus', 'nilai_rata_rata',
        'jurusan_pilihan'
    ];
    
    $errors = validateRequired($_POST, $requiredFields);
    
    if (!empty($errors)) {
        sendResponse(false, implode(', ', $errors));
    }
    
    // Sanitize inputs
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = sanitizeInput($value);
    }
    
    // Validate email if provided
    if (!empty($data['email']) && !validateEmail($data['email'])) {
        sendResponse(false, 'Format email tidak valid');
    }
    
    // Validate NIK
    if (!validateNIK($data['nik'])) {
        sendResponse(false, 'NIK harus 16 digit angka');
    }
    
    // Validate nilai
    if ($data['nilai_rata_rata'] < 0 || $data['nilai_rata_rata'] > 100) {
        sendResponse(false, 'Nilai rata-rata harus antara 0-100');
    }
    
    // Check if NIK already exists
    $checkNIK = $db->prepare("SELECT id FROM siswa WHERE nik = :nik");
    $checkNIK->execute(['nik' => $data['nik']]);
    
    if ($checkNIK->fetchColumn()) {
        sendResponse(false, 'NIK sudah terdaftar');
    }
    
    // Handle file upload
    $fotoFileName = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['foto']);
        
        if (!$uploadResult['success']) {
            sendResponse(false, $uploadResult['message']);
        }
        
        $fotoFileName = $uploadResult['filename'];
    }
    
    // Generate nomor pendaftaran
    $noPendaftaran = generateNoPendaftaran();
    
    // Check if nomor pendaftaran already exists
    do {
        $checkNoPendaftaran = $db->prepare("SELECT id FROM siswa WHERE no_pendaftaran = :no_pendaftaran");
        $checkNoPendaftaran->execute(['no_pendaftaran' => $noPendaftaran]);
        
        if ($checkNoPendaftaran->fetchColumn()) {
            $noPendaftaran = generateNoPendaftaran();
        } else {
            break;
        }
    } while (true);
    
    // Insert data
    $insertQuery = "
        INSERT INTO siswa (
            no_pendaftaran, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir,
            agama, nik, alamat, no_hp, email, nama_ayah, nama_ibu, pekerjaan_ayah,
            pekerjaan_ibu, no_hp_ortu, asal_sekolah, tahun_lulus, nilai_rata_rata,
            jurusan_pilihan, foto
        ) VALUES (
            :no_pendaftaran, :nama_lengkap, :jenis_kelamin, :tempat_lahir, :tanggal_lahir,
            :agama, :nik, :alamat, :no_hp, :email, :nama_ayah, :nama_ibu, :pekerjaan_ayah,
            :pekerjaan_ibu, :no_hp_ortu, :asal_sekolah, :tahun_lulus, :nilai_rata_rata,
            :jurusan_pilihan, :foto
        )
    ";
    
    $insertStmt = $db->prepare($insertQuery);
    
    $insertData = [
        'no_pendaftaran' => $noPendaftaran,
        'nama_lengkap' => $data['nama_lengkap'],
        'jenis_kelamin' => $data['jenis_kelamin'],
        'tempat_lahir' => $data['tempat_lahir'],
        'tanggal_lahir' => $data['tanggal_lahir'],
        'agama' => $data['agama'],
        'nik' => $data['nik'],
        'alamat' => $data['alamat'],
        'no_hp' => $data['no_hp'],
        'email' => !empty($data['email']) ? $data['email'] : null,
        'nama_ayah' => $data['nama_ayah'],
        'nama_ibu' => $data['nama_ibu'],
        'pekerjaan_ayah' => !empty($data['pekerjaan_ayah']) ? $data['pekerjaan_ayah'] : null,
        'pekerjaan_ibu' => !empty($data['pekerjaan_ibu']) ? $data['pekerjaan_ibu'] : null,
        'no_hp_ortu' => $data['no_hp_ortu'],
        'asal_sekolah' => $data['asal_sekolah'],
        'tahun_lulus' => $data['tahun_lulus'],
        'nilai_rata_rata' => $data['nilai_rata_rata'],
        'jurusan_pilihan' => $data['jurusan_pilihan'],
        'foto' => $fotoFileName
    ];
    
    if ($insertStmt->execute($insertData)) {
        sendResponse(true, 'Pendaftaran berhasil!', [
            'no_pendaftaran' => $noPendaftaran
        ]);
    } else {
        sendResponse(false, 'Gagal menyimpan data pendaftaran');
    }
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan sistem. Silakan coba lagi.');
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    sendResponse(false, 'Terjadi kesalahan tidak terduga. Silakan coba lagi.');
}
?>
