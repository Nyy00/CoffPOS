<!-- Global Alert System for CoffPOS -->
<div id="global-alert-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[9999] max-w-md w-full pointer-events-none px-4">
    <!-- Single alert will be displayed here -->
</div>

<!-- Confirmation Modal -->
<div id="global-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9998] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center mb-4">
                <div id="global-confirmation-icon" class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                    <!-- Icon will be inserted here -->
                </div>
                <div>
                    <h3 id="global-confirmation-title" class="text-lg font-semibold text-gray-900">Konfirmasi</h3>
                    <p id="global-confirmation-message" class="text-sm text-gray-600 mt-1">Apakah Anda yakin?</p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-3">
                <button id="global-confirmation-cancel" class="flex-1 py-2 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button id="global-confirmation-confirm" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="global-loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9997] flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <span id="global-loading-message" class="text-gray-700 font-medium">Memproses...</span>
    </div>
</div>

<style>
/* Global Alert animations - Simple vertical only */
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

.global-alert-slide-in {
    animation: fadeInDown 0.3s ease-out;
}

.global-alert-slide-out {
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

.global-modal-fade-in {
    animation: modalFadeIn 0.2s ease-out;
}
</style>

<script>
// Simple Global Alert System
(function() {
    'use strict';
    
    console.log('Global Alert System: Initializing...');
    
    // Wait for DOM to be ready
    function initGlobalAlert() {
        console.log('Global Alert System: DOM Ready, creating instance...');
        
        const alertContainer = document.getElementById('global-alert-container');
        const confirmationModal = document.getElementById('global-confirmation-modal');
        const loadingOverlay = document.getElementById('global-loading-overlay');
        
        if (!alertContainer) {
            console.error('Global Alert System: Alert container not found!');
            return;
        }
        
        console.log('Global Alert System: Elements found, creating system...');
        
        const GlobalAlert = {
            currentConfirmation: null,
            
            // Show success alert
            success: function(message, duration = 4000) {
                console.log('GlobalAlert.success called:', message);
                this.showAlert('success', message, duration);
            },
            
            // Show error alert
            error: function(message, duration = 6000) {
                console.log('GlobalAlert.error called:', message);
                this.showAlert('error', message, duration);
            },
            
            // Show warning alert
            warning: function(message, duration = 5000) {
                console.log('GlobalAlert.warning called:', message);
                this.showAlert('warning', message, duration);
            },
            
            // Show info alert
            info: function(message, duration = 4000) {
                console.log('GlobalAlert.info called:', message);
                this.showAlert('info', message, duration);
            },
            
            // Show alert with custom type
            showAlert: function(type, message, duration = 4000) {
                console.log('GlobalAlert.showAlert called:', type, message);
                
                // Clear existing alerts first
                this.clearAll();
                
                const alertId = 'global-alert-' + Date.now();
                
                const alertConfig = {
                    success: {
                        bgColor: 'bg-green-500',
                        textColor: 'text-white',
                        icon: '✓'
                    },
                    error: {
                        bgColor: 'bg-red-500',
                        textColor: 'text-white',
                        icon: '✗'
                    },
                    warning: {
                        bgColor: 'bg-yellow-500',
                        textColor: 'text-white',
                        icon: '⚠'
                    },
                    info: {
                        bgColor: 'bg-blue-500',
                        textColor: 'text-white',
                        icon: 'ℹ'
                    }
                };
                
                const config = alertConfig[type] || alertConfig.info;
                
                const alertElement = document.createElement('div');
                alertElement.id = alertId;
                alertElement.className = `${config.bgColor} ${config.textColor} p-4 rounded-lg shadow-lg pointer-events-auto global-alert-slide-in flex items-center space-x-3 min-w-0`;
                
                alertElement.innerHTML = `
                    <div class="flex-shrink-0">
                        <span class="text-lg">${config.icon}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium break-words">${message}</p>
                    </div>
                    <button class="flex-shrink-0 ml-2 text-white hover:text-gray-200 transition-colors" onclick="window.globalAlert.dismissAlert('${alertId}')">
                        ✕
                    </button>
                `;
                
                alertContainer.appendChild(alertElement);
                console.log('GlobalAlert: Alert element added to container');
                
                // Auto dismiss after duration
                if (duration > 0) {
                    setTimeout(() => {
                        this.dismissAlert(alertId);
                    }, duration);
                }
                
                return alertId;
            },
            
            // Dismiss specific alert
            dismissAlert: function(alertId) {
                console.log('GlobalAlert.dismissAlert called:', alertId);
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    alertElement.classList.remove('global-alert-slide-in');
                    alertElement.classList.add('global-alert-slide-out');
                    
                    setTimeout(() => {
                        if (alertElement.parentNode) {
                            alertElement.parentNode.removeChild(alertElement);
                        }
                    }, 300);
                }
            },
            
            // Clear all alerts
            clearAll: function() {
                if (alertContainer) {
                    const alerts = alertContainer.querySelectorAll('[id^="global-alert-"]');
                    alerts.forEach(alert => {
                        this.dismissAlert(alert.id);
                    });
                }
            },
            
            // Show confirmation dialog
            confirm: function(options = {}) {
                console.log('GlobalAlert.confirm called:', options);
                return new Promise((resolve) => {
                    const {
                        title = 'Konfirmasi',
                        message = 'Apakah Anda yakin?',
                        confirmText = 'Ya, Lanjutkan',
                        cancelText = 'Batal'
                    } = options;
                    
                    // Set content
                    const titleEl = document.getElementById('global-confirmation-title');
                    const messageEl = document.getElementById('global-confirmation-message');
                    const confirmBtnEl = document.getElementById('global-confirmation-confirm');
                    const cancelBtnEl = document.getElementById('global-confirmation-cancel');
                    
                    if (titleEl) titleEl.textContent = title;
                    if (messageEl) messageEl.textContent = message;
                    if (confirmBtnEl) confirmBtnEl.textContent = confirmText;
                    if (cancelBtnEl) cancelBtnEl.textContent = cancelText;
                    
                    // Store resolve function
                    this.currentConfirmation = resolve;
                    
                    // Show modal
                    if (confirmationModal) {
                        confirmationModal.classList.remove('hidden');
                    }
                });
            },
            
            // Hide confirmation dialog
            hideConfirmation: function(result) {
                console.log('GlobalAlert.hideConfirmation called:', result);
                if (this.currentConfirmation) {
                    this.currentConfirmation(result);
                    this.currentConfirmation = null;
                }
                
                if (confirmationModal) {
                    confirmationModal.classList.add('hidden');
                }
            },
            
            // Show loading overlay
            showLoading: function(message = 'Memproses...') {
                console.log('GlobalAlert.showLoading called:', message);
                const loadingMessage = document.getElementById('global-loading-message');
                if (loadingMessage) {
                    loadingMessage.textContent = message;
                }
                if (loadingOverlay) {
                    loadingOverlay.classList.remove('hidden');
                }
            },
            
            // Hide loading overlay
            hideLoading: function() {
                console.log('GlobalAlert.hideLoading called');
                if (loadingOverlay) {
                    loadingOverlay.classList.add('hidden');
                }
            },
            
            // Quick methods
            confirmDelete: function(itemName = 'item ini') {
                return this.confirm({
                    title: 'Hapus Item',
                    message: `Apakah Anda yakin ingin menghapus ${itemName}?`,
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal'
                });
            },
            
            confirmLogout: function() {
                return this.confirm({
                    title: 'Keluar',
                    message: 'Apakah Anda yakin ingin keluar dari sistem?',
                    confirmText: 'Ya, Keluar',
                    cancelText: 'Batal'
                });
            },
            
            // Session message display
            showSessionMessages: function() {
                console.log('GlobalAlert.showSessionMessages called');
                const successMessage = document.querySelector('meta[name="session-success"]');
                const errorMessage = document.querySelector('meta[name="session-error"]');
                const warningMessage = document.querySelector('meta[name="session-warning"]');
                const infoMessage = document.querySelector('meta[name="session-info"]');
                
                if (successMessage) {
                    this.success(successMessage.getAttribute('content'));
                }
                if (errorMessage) {
                    this.error(errorMessage.getAttribute('content'));
                }
                if (warningMessage) {
                    this.warning(warningMessage.getAttribute('content'));
                }
                if (infoMessage) {
                    this.info(infoMessage.getAttribute('content'));
                }
            }
        };
        
        // Bind confirmation modal events
        const cancelBtn = document.getElementById('global-confirmation-cancel');
        const confirmBtn = document.getElementById('global-confirmation-confirm');
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                GlobalAlert.hideConfirmation(false);
            });
        }
        
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                GlobalAlert.hideConfirmation(true);
            });
        }
        
        // Close modal on backdrop click
        if (confirmationModal) {
            confirmationModal.addEventListener('click', function(e) {
                if (e.target === confirmationModal) {
                    GlobalAlert.hideConfirmation(false);
                }
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (confirmationModal && !confirmationModal.classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    GlobalAlert.hideConfirmation(false);
                } else if (e.key === 'Enter') {
                    GlobalAlert.hideConfirmation(true);
                }
            }
        });
        
        // Make globally available
        window.globalAlert = GlobalAlert;
        
        // Backward compatibility
        window.alert = function(message) {
            GlobalAlert.info(message);
        };
        
        window.confirm = function(message) {
            return GlobalAlert.confirm({ message });
        };
        
        console.log('Global Alert System: Successfully initialized!');
        
        // Auto-show session messages
        GlobalAlert.showSessionMessages();
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGlobalAlert);
    } else {
        initGlobalAlert();
    }
})();
</script>