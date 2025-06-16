// Data Siswa JavaScript - SMA Negeri 1

let dataTable;
let currentData = [];

// Document Ready
$(document).ready(function() {
    initializeDataTable();
    loadStatistics();
    loadSiswaData();
    
    // Filter event listeners
    $('#filterJurusan, #filterStatus, #filterJenisKelamin').on('change', function() {
        applyFilters();
    });
});

// Initialize DataTable
function initializeDataTable() {
    dataTable = $('#tableSiswa').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        columnDefs: [
            { targets: [2], orderable: false }, // Foto column
            { targets: [12], orderable: false } // Aksi column
        ],
        pageLength: 25,
        order: [[11, 'desc']], // Order by Tanggal Daftar desc
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-secondary btn-sm'
            }
        ]
    });
}

// Load Statistics
async function loadStatistics() {
    try {
        const response = await fetch('php/get_statistics.php');
        const data = await response.json();
        
        if (data.success) {
            $('#totalSiswa').text(data.total_siswa);
            $('#totalPending').text(data.total_pending);
            $('#totalDiterima').text(data.total_diterima);
            $('#totalDitolak').text(data.total_ditolak);
        }
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
}

// Load Siswa Data
async function loadSiswaData() {
    try {
        const response = await fetch('php/get_siswa.php');
        const data = await response.json();
        
        if (data.success) {
            currentData = data.data;
            populateTable(currentData);
        } else {
            showAlert('danger', 'Gagal memuat data siswa');
        }
    } catch (error) {
        console.error('Error loading siswa data:', error);
        showAlert('danger', 'Terjadi kesalahan saat memuat data');
    }
}

// Populate Table
function populateTable(data) {
    dataTable.clear();
    
    data.forEach((siswa, index) => {
        const row = [
            index + 1,
            siswa.no_pendaftaran,
            siswa.foto ? `<img src="uploads/${siswa.foto}" alt="Foto" class="profile-img">` : '<span class="text-muted">Tidak ada</span>',
            siswa.nama_lengkap,
            siswa.jenis_kelamin === 'Laki-laki' ? 'L' : 'P',
            `${siswa.tempat_lahir}, ${formatDate(siswa.tanggal_lahir)}`,
            siswa.agama,
            `${siswa.asal_sekolah} (${siswa.tahun_lulus})`,
            siswa.jurusan_pilihan,
            siswa.nilai_rata_rata,
            getStatusBadge(siswa.status),
            formatDate(siswa.created_at),
            getActionButtons(siswa.id, siswa.status)
        ];
        
        dataTable.row.add(row);
    });
    
    dataTable.draw();
}

// Get Status Badge
function getStatusBadge(status) {
    const badges = {
        'Pending': '<span class="badge status-pending">Pending</span>',
        'Diterima': '<span class="badge status-diterima">Diterima</span>',
        'Ditolak': '<span class="badge status-ditolak">Ditolak</span>'
    };
    return badges[status] || status;
}

// Get Action Buttons
function getActionButtons(id, status) {
    return `
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary" onclick="viewDetail(${id})" title="Lihat Detail">
                <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn btn-outline-warning" onclick="editStatus(${id})" title="Edit Status">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-outline-danger" onclick="deleteSiswa(${id})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
}

// Apply Filters
function applyFilters() {
    const jurusanFilter = $('#filterJurusan').val();
    const statusFilter = $('#filterStatus').val();
    const jenisKelaminFilter = $('#filterJenisKelamin').val();
    
    let filteredData = currentData;
    
    if (jurusanFilter) {
        filteredData = filteredData.filter(siswa => siswa.jurusan_pilihan === jurusanFilter);
    }
    
    if (statusFilter) {
        filteredData = filteredData.filter(siswa => siswa.status === statusFilter);
    }
    
    if (jenisKelaminFilter) {
        filteredData = filteredData.filter(siswa => siswa.jenis_kelamin === jenisKelaminFilter);
    }
    
    populateTable(filteredData);
}

// View Detail
async function viewDetail(id) {
    try {
        const response = await fetch(`php/get_siswa_detail.php?id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            const siswa = data.data;
            const modalContent = document.getElementById('modalDetailContent');
            
            modalContent.innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        ${siswa.foto ? `<img src="uploads/${siswa.foto}" alt="Foto" class="img-fluid rounded mb-3" style="max-width: 200px;">` : '<div class="alert alert-light">Tidak ada foto</div>'}
                        <h5>${siswa.nama_lengkap}</h5>
                        <p class="text-muted">${siswa.no_pendaftaran}</p>
                        ${getStatusBadge(siswa.status)}
                    </div>
                    <div class="col-md-8">
                        <h6 class="border-bottom pb-2 mb-3">Data Pribadi</h6>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Jenis Kelamin:</strong></div>
                            <div class="col-sm-8">${siswa.jenis_kelamin}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>TTL:</strong></div>
                            <div class="col-sm-8">${siswa.tempat_lahir}, ${formatDate(siswa.tanggal_lahir)}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Agama:</strong></div>
                            <div class="col-sm-8">${siswa.agama}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>NIK:</strong></div>
                            <div class="col-sm-8">${siswa.nik}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Alamat:</strong></div>
                            <div class="col-sm-8">${siswa.alamat}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>No. HP:</strong></div>
                            <div class="col-sm-8">${siswa.no_hp}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Email:</strong></div>
                            <div class="col-sm-8">${siswa.email || '-'}</div>
                        </div>
                        
                        <h6 class="border-bottom pb-2 mb-3 mt-4">Data Orang Tua</h6>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Nama Ayah:</strong></div>
                            <div class="col-sm-8">${siswa.nama_ayah}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Nama Ibu:</strong></div>
                            <div class="col-sm-8">${siswa.nama_ibu}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Pekerjaan Ayah:</strong></div>
                            <div class="col-sm-8">${siswa.pekerjaan_ayah || '-'}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Pekerjaan Ibu:</strong></div>
                            <div class="col-sm-8">${siswa.pekerjaan_ibu || '-'}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>No. HP Ortu:</strong></div>
                            <div class="col-sm-8">${siswa.no_hp_ortu}</div>
                        </div>
                        
                        <h6 class="border-bottom pb-2 mb-3 mt-4">Data Sekolah</h6>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Asal Sekolah:</strong></div>
                            <div class="col-sm-8">${siswa.asal_sekolah}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Tahun Lulus:</strong></div>
                            <div class="col-sm-8">${siswa.tahun_lulus}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Nilai Rata-rata:</strong></div>
                            <div class="col-sm-8">${siswa.nilai_rata_rata}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Jurusan Pilihan:</strong></div>
                            <div class="col-sm-8">${siswa.jurusan_pilihan}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Tanggal Daftar:</strong></div>
                            <div class="col-sm-8">${formatDate(siswa.created_at)}</div>
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
            modal.show();
        } else {
            showAlert('danger', 'Gagal memuat detail siswa');
        }
    } catch (error) {
        console.error('Error loading detail:', error);
        showAlert('danger', 'Terjadi kesalahan saat memuat detail');
    }
}

// Edit Status
function editStatus(id) {
    // Redirect to admin page for status editing
    window.location.href = `admin.html#kelola-siswa`;
}

// Delete Siswa
function deleteSiswa(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data siswa ini? Tindakan ini tidak dapat dibatalkan.')) {
        performDelete(id);
    }
}

// Perform Delete
async function performDelete(id) {
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
            loadSiswaData();
            loadStatistics();
        } else {
            showAlert('danger', data.message || 'Gagal menghapus data siswa');
        }
    } catch (error) {
        console.error('Error deleting siswa:', error);
        showAlert('danger', 'Terjadi kesalahan saat menghapus data');
    }
}

// Refresh Data
function refreshData() {
    showAlert('info', 'Memuat ulang data...');
    loadSiswaData();
    loadStatistics();
}

// Export Data
function exportData() {
    // Trigger DataTable export
    dataTable.button('.buttons-excel').trigger();
}

// Format Date Function
function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        timeZone: 'Asia/Jakarta'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Show Alert Function
function showAlert(type, message, duration = 5000) {
    // Remove existing alerts
    $('.alert-auto').remove();
    
    // Create new alert
    const alertDiv = $(`
        <div class="alert alert-${type} alert-dismissible fade show alert-auto">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    // Insert at top of page
    $('.container-fluid').prepend(alertDiv);
    
    // Auto remove after duration
    setTimeout(() => {
        alertDiv.remove();
    }, duration);
}
