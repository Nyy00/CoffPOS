/**
 * Toast Notification System
 * Provides success, error, warning, and info notifications
 */

class ToastManager {
    constructor() {
        this.container = null;
        this.toasts = new Map();
        this.init();
    }
    
    init() {
        this.createContainer();
        this.bindEvents();
    }
    
    createContainer() {
        // Check if container already exists
        this.container = document.getElementById('toast-container');
        
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
            this.container.setAttribute('aria-live', 'polite');
            this.container.setAttribute('aria-atomic', 'true');
            document.body.appendChild(this.container);
        }
    }
    
    bindEvents() {
        // Listen for custom toast events
        document.addEventListener('toast:show', (e) => {
            this.show(e.detail.type, e.detail.message, e.detail.options);
        });
        
        document.addEventListener('toast:clear', () => {
            this.clearAll();
        });
    }
    
    show(type = 'info', message = '', options = {}) {
        const config = {
            duration: 5000,
            closable: true,
            persistent: false,
            ...options
        };
        
        const toast = this.createToast(type, message, config);
        const toastId = this.generateId();
        
        toast.setAttribute('data-toast-id', toastId);
        this.toasts.set(toastId, toast);
        
        // Add to container
        this.container.appendChild(toast);
        
        // Trigger animation
        requestAnimationFrame(() => {
            toast.classList.add('toast-show');
        });
        
        // Auto-dismiss if not persistent
        if (!config.persistent && config.duration > 0) {
            setTimeout(() => {
                this.dismiss(toastId);
            }, config.duration);
        }
        
        return toastId;
    }
    
    createToast(type, message, config) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;
        
        const iconSvg = this.getIcon(type);
        const bgColor = this.getBgColor(type);
        const textColor = this.getTextColor(type);
        const borderColor = this.getBorderColor(type);
        
        toast.innerHTML = `
            <div class="flex max-w-sm w-full ${bgColor} shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="flex-1 w-0 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            ${iconSvg}
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium ${textColor}">
                                ${this.escapeHtml(message)}
                            </p>
                        </div>
                        ${config.closable ? `
                            <div class="ml-4 flex-shrink-0 flex">
                                <button type="button" 
                                        class="toast-close bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        aria-label="Close notification">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        // Bind close button
        if (config.closable) {
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                const toastId = toast.getAttribute('data-toast-id');
                this.dismiss(toastId);
            });
        }
        
        return toast;
    }
    
    dismiss(toastId) {
        const toast = this.toasts.get(toastId);
        if (!toast) return;
        
        // Animate out
        toast.classList.remove('toast-show');
        toast.classList.add('translate-x-full', 'opacity-0');
        
        // Remove from DOM after animation
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            this.toasts.delete(toastId);
        }, 300);
    }
    
    clearAll() {
        this.toasts.forEach((toast, toastId) => {
            this.dismiss(toastId);
        });
    }
    
    success(message, options = {}) {
        return this.show('success', message, options);
    }
    
    error(message, options = {}) {
        return this.show('error', message, { duration: 7000, ...options });
    }
    
    warning(message, options = {}) {
        return this.show('warning', message, options);
    }
    
    info(message, options = {}) {
        return this.show('info', message, options);
    }
    
    getIcon(type) {
        const icons = {
            success: `
                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `,
            error: `
                <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `,
            warning: `
                <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            `,
            info: `
                <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `
        };
        
        return icons[type] || icons.info;
    }
    
    getBgColor(type) {
        const colors = {
            success: 'bg-white',
            error: 'bg-white',
            warning: 'bg-white',
            info: 'bg-white'
        };
        
        return colors[type] || colors.info;
    }
    
    getTextColor(type) {
        const colors = {
            success: 'text-gray-900',
            error: 'text-gray-900',
            warning: 'text-gray-900',
            info: 'text-gray-900'
        };
        
        return colors[type] || colors.info;
    }
    
    getBorderColor(type) {
        const colors = {
            success: 'border-green-200',
            error: 'border-red-200',
            warning: 'border-yellow-200',
            info: 'border-blue-200'
        };
        
        return colors[type] || colors.info;
    }
    
    generateId() {
        return 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Create global instance
window.Toast = new ToastManager();

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    .toast-show {
        transform: translateX(0) !important;
        opacity: 1 !important;
    }
    
    .toast {
        transition: all 0.3s ease-in-out;
    }
    
    .toast:hover {
        transform: translateX(0) scale(1.02);
    }
`;
document.head.appendChild(style);

// Helper functions for easy access
window.showToast = {
    success: (message, options) => window.Toast.success(message, options),
    error: (message, options) => window.Toast.error(message, options),
    warning: (message, options) => window.Toast.warning(message, options),
    info: (message, options) => window.Toast.info(message, options)
};

// Laravel integration - show flash messages as toasts
document.addEventListener('DOMContentLoaded', () => {
    // Check for Laravel flash messages
    const flashMessages = document.querySelectorAll('[data-flash-message]');
    flashMessages.forEach(element => {
        const type = element.dataset.flashType || 'info';
        const message = element.dataset.flashMessage || element.textContent.trim();
        
        if (message) {
            window.Toast.show(type, message);
        }
        
        // Hide the original flash message element
        element.style.display = 'none';
    });
});

export default ToastManager;