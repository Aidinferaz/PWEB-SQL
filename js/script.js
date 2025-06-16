// Main JavaScript File - SMA Negeri 1 Pendaftaran Siswa Baru

// Global Variables
let totalSiswaData = 0;

// Document Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize page
    initializePage();
    
    // Load total siswa count for homepage
    if (document.getElementById('total-siswa')) {
        loadTotalSiswa();
    }
    
    // Initialize form validation
    initializeFormValidation();
});

// Initialize Page
function initializePage() {
    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Initialize tooltips if Bootstrap is loaded
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Load Total Siswa Count
async function loadTotalSiswa() {
    try {
        const response = await fetch('php/get_statistics.php');
        const data = await response.json();
        
        if (data.success) {
            const totalElement = document.getElementById('total-siswa');
            if (totalElement) {
                animateCount(totalElement, 0, data.total_siswa, 2000);
            }
        }
    } catch (error) {
        console.error('Error loading total siswa:', error);
    }
}

// Animate Count Effect
function animateCount(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.textContent = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Initialize Form Validation
function initializeFormValidation() {
    const form = document.getElementById('formPendaftaran');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
        
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', clearFieldError);
        });
        
        // NIK validation
        const nikInput = document.getElementById('nik');
        if (nikInput) {
            nikInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 16) {
                    this.value = this.value.slice(0, 16);
                }
            });
        }
        
        // Phone number validation
        const phoneInputs = document.querySelectorAll('#no_hp, #no_hp_ortu');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        });
        
        // File validation
        const fileInput = document.getElementById('foto');
        if (fileInput) {
            fileInput.addEventListener('change', validateFile);
        }
    }
}

// Handle Form Submit
async function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Validate form
    if (!validateForm(form)) {
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftar...';
    
    try {
        const formData = new FormData(form);
        const response = await fetch('php/proses_daftar.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success modal
            showSuccessModal(result.no_pendaftaran);
            form.reset();
        } else {
            showAlert('danger', result.message || 'Terjadi kesalahan saat mendaftar');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        showAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Validate Form
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField({ target: field })) {
            isValid = false;
        }
    });
    
    // Additional validations
    const email = form.querySelector('#email');
    if (email && email.value && !isValidEmail(email.value)) {
        showFieldError(email, 'Format email tidak valid');
        isValid = false;
    }
    
    const nik = form.querySelector('#nik');
    if (nik && nik.value.length !== 16) {
        showFieldError(nik, 'NIK harus 16 digit');
        isValid = false;
    }
    
    return isValid;
}

// Validate Individual Field
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    
    clearFieldError(field);
    
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Field ini wajib diisi');
        return false;
    }
    
    if (field.type === 'email' && value && !isValidEmail(value)) {
        showFieldError(field, 'Format email tidak valid');
        return false;
    }
    
    if (field.id === 'nik' && value && value.length !== 16) {
        showFieldError(field, 'NIK harus 16 digit');
        return false;
    }
    
    if (field.type === 'tel' && value && !/^[\d-+().\s]+$/.test(value)) {
        showFieldError(field, 'Format nomor telepon tidak valid');
        return false;
    }
    
    return true;
}

// Show Field Error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    let errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        field.parentNode.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

// Clear Field Error
function clearFieldError(field) {
    if (typeof field === 'object' && field.target) {
        field = field.target;
    }
    
    field.classList.remove('is-invalid');
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Validate File
function validateFile(e) {
    const file = e.target.files[0];
    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    
    if (file) {
        if (file.size > maxSize) {
            showFieldError(e.target, 'Ukuran file maksimal 2MB');
            e.target.value = '';
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            showFieldError(e.target, 'Format file harus JPG, JPEG, atau PNG');
            e.target.value = '';
            return false;
        }
        
        clearFieldError(e.target);
    }
    
    return true;
}

// Utility Functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show Success Modal
function showSuccessModal(noPendaftaran) {
    const modal = document.getElementById('modalSukses');
    const noPendaftaranElement = document.getElementById('noPendaftaran');
    
    if (modal && noPendaftaranElement) {
        noPendaftaranElement.textContent = noPendaftaran;
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }
}

// Show Alert
function showAlert(type, message, duration = 5000) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-auto');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-auto`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at top of page
    const container = document.querySelector('.container, .container-fluid');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto remove after duration
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, duration);
    }
}

// Format Date
function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        timeZone: 'Asia/Jakarta'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Format Currency
function formatCurrency(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(number);
}

// Export Functions for Global Use
window.pendaftaranApp = {
    loadTotalSiswa,
    showAlert,
    formatDate,
    formatCurrency,
    validateForm,
    showSuccessModal
};
