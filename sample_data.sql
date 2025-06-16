-- Sample Data for Pendaftaran Siswa Baru
-- Execute this after running install.php

USE pendaftaran_siswa;

-- Insert sample students data
INSERT INTO siswa (
    no_pendaftaran, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir,
    agama, nik, alamat, no_hp, email, nama_ayah, nama_ibu, pekerjaan_ayah,
    pekerjaan_ibu, no_hp_ortu, asal_sekolah, tahun_lulus, nilai_rata_rata,
    jurusan_pilihan, status, created_at
) VALUES
('PSB2025010001', 'Ahmad Fauzi Rahman', 'Laki-laki', 'Jakarta', '2008-03-15',
 'Islam', '3173081503080001', 'Jl. Merdeka No. 123, Jakarta Pusat', '081234567890', 
 'ahmad.fauzi@email.com', 'Rahman Abdullah', 'Siti Aminah', 'Guru', 'Ibu Rumah Tangga',
 '081234567891', 'SMP Negeri 5 Jakarta', 2024, 87.5, 'IPA', 'Diterima', '2025-01-10 08:30:00'),

('PSB2025010002', 'Sari Dewi Lestari', 'Perempuan', 'Bandung', '2008-07-22',
 'Islam', '3273162207080002', 'Jl. Sudirman No. 456, Bandung', '081234567892',
 'sari.dewi@email.com', 'Bambang Lestari', 'Dewi Sartika', 'Dokter', 'Perawat',
 '081234567893', 'SMP Negeri 3 Bandung', 2024, 92.3, 'IPA', 'Diterima', '2025-01-11 09:15:00'),

('PSB2025010003', 'Muhammad Rizki Pratama', 'Laki-laki', 'Surabaya', '2008-11-08',
 'Islam', '3578110811080003', 'Jl. Pemuda No. 789, Surabaya', '081234567894',
 'rizki.pratama@email.com', 'Pratama Wijaya', 'Indah Sari', 'Pengusaha', 'Guru',
 '081234567895', 'SMP Al-Hikmah Surabaya', 2024, 78.9, 'IPS', 'Pending', '2025-01-12 10:45:00'),

('PSB2025010004', 'Nur Fitria Handayani', 'Perempuan', 'Yogyakarta', '2008-05-30',
 'Islam', '3471053005080004', 'Jl. Malioboro No. 321, Yogyakarta', '081234567896',
 'fitria.handayani@email.com', 'Handayani Susilo', 'Fitri Rahayu', 'Dosen', 'Akuntan',
 '081234567897', 'SMP Negeri 2 Yogyakarta', 2024, 89.7, 'Bahasa', 'Diterima', '2025-01-13 11:20:00'),

('PSB2025010005', 'Dimas Adi Nugroho', 'Laki-laki', 'Semarang', '2008-09-14',
 'Kristen', '3374141409080005', 'Jl. Pahlawan No. 654, Semarang', '081234567898',
 'dimas.nugroho@email.com', 'Nugroho Santoso', 'Maria Magdalena', 'Insinyur', 'Dokter',
 '081234567899', 'SMP Kristen Petra Semarang', 2024, 85.2, 'IPA', 'Pending', '2025-01-14 14:30:00'),

('PSB2025010006', 'Anisa Putri Maharani', 'Perempuan', 'Medan', '2008-12-03',
 'Islam', '1271240312080006', 'Jl. Gatot Subroto No. 987, Medan', '081234567900',
 'anisa.maharani@email.com', 'Maharani Budi', 'Putri Sejati', 'Pilot', 'Pramugari',
 '081234567901', 'SMP Negeri 7 Medan', 2024, 91.8, 'IPS', 'Diterima', '2025-01-15 16:45:00'),

('PSB2025010007', 'Kevin Alexander Tanujaya', 'Laki-laki', 'Palembang', '2008-04-18',
 'Kristen', '1671180404080007', 'Jl. Veteran No. 147, Palembang', '081234567902',
 'kevin.tanujaya@email.com', 'Alexander Tanujaya', 'Susanti Lie', 'Direktur', 'Manager',
 '081234567903', 'SMP Xaverius Palembang', 2024, 88.4, 'Bahasa', 'Pending', '2025-01-16 08:15:00'),

('PSB2025010008', 'Rika Amelia Sari', 'Perempuan', 'Makassar', '2008-08-25',
 'Islam', '7371252508080008', 'Jl. Jendral Sudirman No. 258, Makassar', '081234567904',
 'rika.sari@email.com', 'Sari Wijaya', 'Amelia Rahman', 'Polisi', 'Hakim',
 '081234567905', 'SMP Negeri 1 Makassar', 2024, 79.6, 'IPS', 'Ditolak', '2025-01-17 13:30:00'),

('PSB2025010009', 'Fajar Ramadhan Putra', 'Laki-laki', 'Denpasar', '2008-06-12',
 'Hindu', '5171121206080009', 'Jl. Gajah Mada No. 369, Denpasar', '081234567906',
 'fajar.putra@email.com', 'Putra Ramadhan', 'Kadek Sari', 'Seniman', 'Desainer',
 '081234567907', 'SMP Saraswati Denpasar', 2024, 86.1, 'IPA', 'Pending', '2025-01-18 09:50:00'),

('PSB2025010010', 'Luna Purnama Sari', 'Perempuan', 'Banjarmasin', '2008-10-07',
 'Islam', '6371070710080010', 'Jl. Lambung Mangkurat No. 741, Banjarmasin', '081234567908',
 'luna.sari@email.com', 'Purnama Agung', 'Sari Bulan', 'Nelayan', 'Pedagang',
 '081234567909', 'SMP Negeri 4 Banjarmasin', 2024, 83.7, 'Bahasa', 'Diterima', '2025-01-19 15:25:00');

-- Update some records with additional information
UPDATE siswa SET keterangan = 'Siswa berprestasi dengan nilai sangat baik' WHERE status = 'Diterima';
UPDATE siswa SET keterangan = 'Menunggu verifikasi dokumen' WHERE status = 'Pending';
UPDATE siswa SET keterangan = 'Nilai di bawah standar minimum' WHERE status = 'Ditolak';

-- Show summary
SELECT 
    'Total Pendaftar' as Keterangan,
    COUNT(*) as Jumlah
FROM siswa
UNION ALL
SELECT 
    CONCAT('Status: ', status) as Keterangan,
    COUNT(*) as Jumlah
FROM siswa
GROUP BY status
UNION ALL
SELECT 
    CONCAT('Jurusan: ', jurusan_pilihan) as Keterangan,
    COUNT(*) as Jumlah
FROM siswa
GROUP BY jurusan_pilihan;
