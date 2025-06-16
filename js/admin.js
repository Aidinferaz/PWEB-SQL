// Admin JavaScript - SMA Negeri 1

let adminTable;
let currentUser = null;

// Document Ready
$(document).ready(function() {
    // Check if user is already logged in
    checkLogin();
    
    // Initialize login form
    $('#loginForm').on('submit', handleLogin);
    
    // Initialize admin forms
    $('#formUpdateStatus').on('submit', handleUpdateStatus);
    $('#pengaturanForm').on('submit', handlePengaturan);
});

// Check Login Status
function checkLogin() {
    const isLoggedIn = sessionStorage.getItem('adminLoggedIn');
    if (isLoggedIn === 'true') {
        showAdminPanel();
        loadDashboard();
    }
}

// Handle Login
async function handleLogin(e) {
    e.preventDefault();
    
    const username = $('#username').val();
    const password = $('#password').val();
    const submitBtn = $('#loginForm button[type="submit"]');
    
    // Simple validation (in production, use proper authentication)
    if (username === 'admin' && password === 'admin123') {
        sessionStorage.setItem('adminLoggedIn', 'true');
        sessionStorage.setItem('adminUsername', username);
        currentUser = username;
        
        showAlert('success', 'Login berhasil! Selamat datang, Admin.');
        showAdminPanel();
        loadDashboard();
    } else {
        showAlert('danger', 'Username atau password salah!');
    }
}

// Show Admin Panel
function showAdminPanel() {
    $('#loginSection').hide();
    $('#adminPanel').show();
    currentUser = sessionStorage.getItem('adminUsername');
}

// Logout
function logout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        sessionStorage.removeItem('adminLoggedIn');
        sessionStorage.removeItem('adminUsername');
        currentUser = null;
        
        $('#loginSection').show();
        $('#adminPanel').hide();
        
        // Reset forms
        $('#loginForm')[0].reset();
        
        showAlert('info', 'Anda telah logout.');
    }
}

// Show Section
function showSection(sectionId) {
    // Hide all sections
    $('.content-section').hide();
    
    // Show selected section
    $(`#${sectionId}`).show();
    
    // Update active menu
    $('.list-group-item').removeClass('active');
    $(`a[href="#${sectionId}"]`).addClass('active');
    
    // Load section data
    switch(sectionId) {
        case 'dashboard':
            loadDashboard();
            break;
        case 'kelola-siswa':
            loadKelolaSiswa();
            break;
        case 'laporan':
            loadLaporan();
            break;
        case 'pengaturan':
            loadPengaturan();
            break;
    }
}

// Load Dashboard
async function loadDashboard() {
    try {
        // Load statistics
        const response = await fetch('php/get_statistics.php');
        const data = await response.json();
        
        if (data.success) {
            $('#dashTotalSiswa').text(data.total_siswa);
            $('#dashTotalPending').text(data.total_pending);
            $('#dashTotalDiterima').text(data.total_diterima);
            $('#dashTotalDitolak').text(data.total_ditolak);
        }
        
        // Load recent applications
        await loadRecentApplications();
        
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

// Load Recent Applications
async function loadRecentApplications() {
    try {
        const response = await fetch('php/get_recent_applications.php');
        const data = await response.json();
        
        if (data.success) {
            const tbody = $('#recentApplications');
            tbody.empty();
            
            data.data.forEach(siswa => {
                const row = `
                    <tr>
                        <td>${siswa.no_pendaftaran}</td>
                        <td>${siswa.nama_lengkap}</td>
                        <td>${siswa.jurusan_pilihan}</td>
                        <td>${getStatusBadge(siswa.status)}</td>
                        <td>${formatDate(siswa.created_at)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewDetailAdmin(${siswa.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="updateStatusModal(${siswa.id}, '${siswa.status}')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }
    } catch (error) {
        console.error('Error loading recent applications:', error);
    }
}

// Load Kelola Siswa
async function loadKelolaSiswa() {
    try {
        const response = await fetch('php/get_siswa.php');
        const data = await response.json();
        
        if (data.success) {
            if (adminTable) {
                adminTable.destroy();
            }
            
            const tbody = $('#tableKelolaSiswa tbody');
            tbody.empty();
            
            data.data.forEach(siswa => {
                const row = `
                    <tr>
                        <td>${siswa.no_pendaftaran}</td>
                        <td>${siswa.nama_lengkap}</td>
                        <td>${siswa.jurusan_pilihan}</td>
                        <td>${siswa.nilai_rata_rata}</td>
                        <td>${getStatusBadge(siswa.status)}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="viewDetailAdmin(${siswa.id})" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="updateStatusModal(${siswa.id}, '${siswa.status}')" title="Edit Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteSiswaAdmin(${siswa.id})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
            
            // Initialize DataTable
            adminTable = $('#tableKelolaSiswa').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 25,
                order: [[0, 'desc']]
            });
        }
    } catch (error) {
        console.error('Error loading kelola siswa:', error);
    }
}

// Update Status Modal
function updateStatusModal(id, currentStatus) {
    $('#updateSiswaId').val(id);
    $('#updateStatus').val(currentStatus);
    $('#updateKeterangan').val('');
    
    const modal = new bootstrap.Modal(document.getElementById('modalUpdateStatus'));
    modal.show();
}

// Save Status Update
async function saveStatusUpdate() {
    const id = $('#updateSiswaId').val();
    const status = $('#updateStatus').val();
    const keterangan = $('#updateKeterangan').val();
    
    try {
        const response = await fetch('php/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: id,
                status: status,
                keterangan: keterangan
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('success', 'Status berhasil diperbarui');
            $('#modalUpdateStatus').modal('hide');
            
            // Refresh current section
            const activeSection = $('.list-group-item.active').attr('href').substring(1);
            if (activeSection === 'kelola-siswa') {
                loadKelolaSiswa();
            } else if (activeSection === 'dashboard') {
                loadDashboard();
            }
        } else {
            showAlert('danger', data.message || 'Gagal memperbarui status');
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showAlert('danger', 'Terjadi kesalahan saat memperbarui status');
    }
}

// View Detail Admin
async function viewDetailAdmin(id) {
    // Use the same function from data-siswa.js
    if (typeof viewDetail === 'function') {
        viewDetail(id);
    } else {
        showAlert('info', 'Fitur detail akan segera tersedia');
    }
}

// Delete Siswa Admin
function deleteSiswaAdmin(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data siswa ini? Tindakan ini tidak dapat dibatalkan.')) {
        performDeleteAdmin(id);
    }
}

// Perform Delete Admin
async function performDeleteAdmin(id) {
    try {
        const response = await fetch('php/delete_siswa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('success', 'Data siswa berhasil dihapus');
            loadKelolaSiswa();
            loadDashboard();
        } else {
            showAlert('danger', data.message || 'Gagal menghapus data siswa');
        }
    } catch (error) {
        console.error('Error deleting siswa:', error);
        showAlert('danger', 'Terjadi kesalahan saat menghapus data');
    }
}

// Load Laporan
async function loadLaporan() {
    try {
        const response = await fetch('php/get_laporan.php');
        const data = await response.json();
        
        if (data.success) {
            // Create charts
            createJurusanChart(data.jurusan);
            createStatusChart(data.status);
        }
    } catch (error) {
        console.error('Error loading laporan:', error);
    }
}

// Create Jurusan Chart
function createJurusanChart(data) {
    const ctx = document.getElementById('chartJurusan').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(data),
            datasets: [{
                data: Object.values(data),
                backgroundColor: [
                    '#0d6efd',
                    '#198754',
                    '#ffc107'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Create Status Chart
function createStatusChart(data) {
    const ctx = document.getElementById('chartStatus').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: 'Jumlah Siswa',
                data: Object.values(data),
                backgroundColor: [
                    '#ffc107',
                    '#198754',
                    '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Export Laporan
function exportLaporan(format) {
    if (format === 'excel') {
        window.open('php/export_excel.php', '_blank');
    } else if (format === 'pdf') {
        window.open('php/export_pdf.php', '_blank');
    }
}

// Load Pengaturan
function loadPengaturan() {
    // Load current settings from server or localStorage
    const settings = JSON.parse(localStorage.getItem('adminSettings') || '{}');
    
    $('#statusPendaftaran').val(settings.statusPendaftaran || '1');
    $('#batasNilai').val(settings.batasNilai || '75');
    $('#kapasitasIPA').val(settings.kapasitasIPA || '108');
    $('#kapasitasIPS').val(settings.kapasitasIPS || '72');
    $('#kapasitasBahasa').val(settings.kapasitasBahasa || '36');
}

// Handle Pengaturan
function handlePengaturan(e) {
    e.preventDefault();
    
    const settings = {
        statusPendaftaran: $('#statusPendaftaran').val(),
        batasNilai: $('#batasNilai').val(),
        kapasitasIPA: $('#kapasitasIPA').val(),
        kapasitasIPS: $('#kapasitasIPS').val(),
        kapasitasBahasa: $('#kapasitasBahasa').val()
    };
    
    // Save to localStorage (in production, save to server)
    localStorage.setItem('adminSettings', JSON.stringify(settings));
    
    // Handle password change
    const newPassword = $('#newPassword').val();
    const confirmPassword = $('#confirmPassword').val();
    
    if (newPassword && newPassword === confirmPassword) {
        // In production, save to server
        showAlert('success', 'Pengaturan dan password berhasil disimpan');
        $('#newPassword, #confirmPassword').val('');
    } else if (newPassword && newPassword !== confirmPassword) {
        showAlert('danger', 'Konfirmasi password tidak cocok');
        return;
    } else {
        showAlert('success', 'Pengaturan berhasil disimpan');
    }
}

// Backup Database
function backupDatabase() {
    if (confirm('Apakah Anda yakin ingin melakukan backup database?')) {
        window.open('php/backup_database.php', '_blank');
        showAlert('info', 'Proses backup sedang berlangsung...');
    }
}

// Clear Old Data
function clearOldData() {
    if (confirm('Apakah Anda yakin ingin menghapus data lama? Tindakan ini tidak dapat dibatalkan.')) {
        // In production, implement server-side cleanup
        showAlert('warning', 'Fitur pembersihan data akan segera tersedia');
    }
}

// Refresh Kelola Siswa
function refreshKelolaSiswa() {
    showAlert('info', 'Memuat ulang data...');
    loadKelolaSiswa();
}

// Utility Functions
function getStatusBadge(status) {
    const badges = {
        'Pending': '<span class="badge bg-warning text-dark">Pending</span>',
        'Diterima': '<span class="badge bg-success">Diterima</span>',
        'Ditolak': '<span class="badge bg-danger">Ditolak</span>'
    };
    return badges[status] || status;
}

function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        timeZone: 'Asia/Jakarta'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

function showAlert(type, message, duration = 5000) {
    $('.alert-auto').remove();
    
    const alertDiv = $(`
        <div class="alert alert-${type} alert-dismissible fade show alert-auto">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('.container-fluid').prepend(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, duration);
}
