<!-- Alert System for POS -->
<div id="alert-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[9999] max-w-md w-full pointer-events-none px-4">
    <!-- Single alert will be displayed here -->
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9998] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center mb-4">
                <div id="confirmation-icon" class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                    <!-- Icon will be inserted here -->
                </div>
                <div>
                    <h3 id="confirmation-title" class="text-lg font-semibold text-gray-900">Konfirmasi</h3>
                    <p id="confirmation-message" class="text-sm text-gray-600 mt-1">Apakah Anda yakin?</p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-3">
                <button id="confirmation-cancel" class="flex-1 py-2 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button id="confirmation-confirm" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9997] flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span id="loading-message" class="text-gray-700 font-medium">Memproses...</span>
    </div>
</div>

<style>
/* Alert animations - Simple vertical only */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOutUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

.alert-slide-in {
    animation: fadeInDown 0.3s ease-out;
}

.alert-slide-out {
    animation: fadeOutUp 0.3s ease-in;
}

/* Modal animations */
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-fade-in {
    animation: modalFadeIn 0.2s ease-out;
}
</style>

<script>
class POSAlertSystem {
    constructor() {
        this.alertContainer = document.getElementById('alert-container');
        this.confirmationModal = document.getElementById('confirmation-modal');
        this.loadingOverlay = document.getElementById('loading-overlay');
        this.alertQueue = [];
        this.currentConfirmation = null;
        
        this.init();
    }
    
    init() {
        // Bind confirmation modal events
        document.getElementById('confirmation-cancel').addEventListener('click', () => {
            this.hideConfirmation(false);
        });
        
        document.getElementById('confirmation-confirm').addEventListener('click', () => {
            this.hideConfirmation(true);
        });
        
        // Close modal on backdrop click
        this.confirmationModal.addEventListener('click', (e) => {
            if (e.target === this.confirmationModal) {
                this.hideConfirmation(false);
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (this.confirmationModal && !this.confirmationModal.classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    this.hideConfirmation(false);
                } else if (e.key === 'Enter') {
                    this.hideConfirmation(true);
                }
            }
        });
    }
    
    // Show success alert
    success(message, duration = 4000) {
        this.showAlert('success', message, duration);
    }
    
    // Show error alert
    error(message, duration = 6000) {
        this.showAlert('error', message, duration);
    }
    
    // Show warning alert
    warning(message, duration = 5000) {
        this.showAlert('warning', message, duration);
    }
    
    // Show info alert
    info(message, duration = 4000) {
        this.showAlert('info', message, duration);
    }
    
    // Show alert with custom type
    showAlert(type, message, duration = 4000) {
        // Clear existing alerts first (replace old with new)
        this.clearAll();
        
        const alertId = 'alert-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        
        const alertConfig = {
            success: {
                bgColor: 'bg-green-500',
                textColor: 'text-white',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`
            },
            error: {
                bgColor: 'bg-red-500',
                textColor: 'text-white',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`
            },
            warning: {
                bgColor: 'bg-yellow-500',
                textColor: 'text-white',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>`
            },
            info: {
                bgColor: 'bg-blue-500',
                textColor: 'text-white',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`
            }
        };
        
        const config = alertConfig[type] || alertConfig.info;
        
        const alertElement = document.createElement('div');
        alertElement.id = alertId;
        alertElement.className = `${config.bgColor} ${config.textColor} p-4 rounded-lg shadow-lg pointer-events-auto alert-slide-in flex items-center space-x-3 min-w-0`;
        
        alertElement.innerHTML = `
            <div class="flex-shrink-0">
                ${config.icon}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium break-words">${message}</p>
            </div>
            <button class="flex-shrink-0 ml-2 text-white hover:text-gray-200 transition-colors" onclick="window.posAlert.dismissAlert('${alertId}')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        this.alertContainer.appendChild(alertElement);
        
        // Auto dismiss after duration
        if (duration > 0) {
            setTimeout(() => {
                this.dismissAlert(alertId);
            }, duration);
        }
        
        return alertId;
    }
    
    // Dismiss specific alert
    dismissAlert(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.classList.remove('alert-slide-in');
            alertElement.classList.add('alert-slide-out');
            
            setTimeout(() => {
                if (alertElement.parentNode) {
                    alertElement.parentNode.removeChild(alertElement);
                }
            }, 300);
        }
    }
    
    // Clear all alerts
    clearAll() {
        const alerts = this.alertContainer.querySelectorAll('[id^="alert-"]');
        alerts.forEach(alert => {
            this.dismissAlert(alert.id);
        });
    }
    
    // Show confirmation dialog
    confirm(options = {}) {
        return new Promise((resolve) => {
            const {
                title = 'Konfirmasi',
                message = 'Apakah Anda yakin?',
                confirmText = 'Ya, Lanjutkan',
                cancelText = 'Batal',
                type = 'warning',
                confirmButtonClass = 'bg-blue-600 hover:bg-blue-700'
            } = options;
            
            // Set content
            document.getElementById('confirmation-title').textContent = title;
            document.getElementById('confirmation-message').textContent = message;
            document.getElementById('confirmation-confirm').textContent = confirmText;
            document.getElementById('confirmation-cancel').textContent = cancelText;
            
            // Set icon and colors based on type
            const iconContainer = document.getElementById('confirmation-icon');
            const confirmButton = document.getElementById('confirmation-confirm');
            
            // Reset classes
            iconContainer.className = 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3';
            confirmButton.className = 'flex-1 py-2 px-4 text-white rounded-lg transition-colors font-medium';
            
            switch (type) {
                case 'danger':
                    iconContainer.classList.add('bg-red-100');
                    iconContainer.innerHTML = `<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>`;
                    confirmButton.classList.add('bg-red-600', 'hover:bg-red-700');
                    break;
                case 'success':
                    iconContainer.classList.add('bg-green-100');
                    iconContainer.innerHTML = `<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>`;
                    confirmButton.classList.add('bg-green-600', 'hover:bg-green-700');
                    break;
                case 'info':
                    iconContainer.classList.add('bg-blue-100');
                    iconContainer.innerHTML = `<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>`;
                    confirmButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    break;
                default: // warning
                    iconContainer.classList.add('bg-yellow-100');
                    iconContainer.innerHTML = `<svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>`;
                    confirmButton.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
            }
            
            // Store resolve function
            this.currentConfirmation = resolve;
            
            // Show modal
            this.confirmationModal.classList.remove('hidden');
            this.confirmationModal.querySelector('.bg-white').classList.add('modal-fade-in');
        });
    }
    
    // Hide confirmation dialog
    hideConfirmation(result) {
        if (this.currentConfirmation) {
            this.currentConfirmation(result);
            this.currentConfirmation = null;
        }
        
        this.confirmationModal.classList.add('hidden');
        this.confirmationModal.querySelector('.bg-white').classList.remove('modal-fade-in');
    }
    
    // Show loading overlay
    showLoading(message = 'Memproses...') {
        document.getElementById('loading-message').textContent = message;
        this.loadingOverlay.classList.remove('hidden');
    }
    
    // Hide loading overlay
    hideLoading() {
        this.loadingOverlay.classList.add('hidden');
    }
    
    // Quick methods for common POS scenarios
    transactionSuccess(transactionCode) {
        this.success(`Transaksi ${transactionCode} berhasil diproses!`);
    }
    
    paymentError(message) {
        this.error(`Pembayaran gagal: ${message}`);
    }
    
    stockWarning(productName, stock) {
        this.warning(`Stok ${productName} tinggal ${stock} item!`);
    }
    
    productAdded(productName) {
        this.success(`${productName} ditambahkan ke keranjang`);
    }
    
    productRemoved(productName) {
        this.success(`${productName} dihapus dari keranjang`);
    }
    
    cartCleared() {
        this.success('Keranjang berhasil dikosongkan');
    }
    
    transactionHeld() {
        this.success('Transaksi berhasil ditahan');
    }
    
    transactionResumed() {
        this.success('Transaksi berhasil dilanjutkan');
    }
    
    insufficientPayment() {
        this.error('Jumlah pembayaran tidak mencukupi');
    }
    
    emptyCart() {
        this.warning('Keranjang masih kosong');
    }
    
    confirmDelete(itemName) {
        return this.confirm({
            title: 'Hapus Item',
            message: `Apakah Anda yakin ingin menghapus ${itemName} dari keranjang?`,
            type: 'danger',
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal'
        });
    }
    
    confirmClearCart() {
        return this.confirm({
            title: 'Kosongkan Keranjang',
            message: 'Apakah Anda yakin ingin mengosongkan semua item di keranjang?',
            type: 'warning',
            confirmText: 'Ya, Kosongkan',
            cancelText: 'Batal'
        });
    }
    
    confirmLogout() {
        return this.confirm({
            title: 'Keluar',
            message: 'Apakah Anda yakin ingin keluar dari sistem POS?',
            type: 'info',
            confirmText: 'Ya, Keluar',
            cancelText: 'Batal'
        });
    }
}

// Initialize alert system
window.posAlert = new POSAlertSystem();

// Backward compatibility - replace default alert/confirm
window.alert = function(message) {
    window.posAlert.info(message);
};

window.confirm = function(message) {
    return window.posAlert.confirm({ message });
};
</script>