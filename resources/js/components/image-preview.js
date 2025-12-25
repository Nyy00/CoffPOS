/**
 * Image Preview Component
 * Handles image preview functionality for file uploads
 */

class ImagePreview {
    constructor(fileInput, options = {}) {
        this.fileInput = fileInput;
        this.options = {
            previewContainer: null,
            maxFileSize: 2048 * 1024, // 2MB
            allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
            showFileName: true,
            showFileSize: true,
            showRemoveButton: true,
            multiple: false,
            ...options
        };
        
        this.previews = [];
        this.init();
    }
    
    init() {
        if (!this.fileInput) return;
        
        this.createPreviewContainer();
        this.bindEvents();
        this.checkExistingImage();
    }
    
    createPreviewContainer() {
        if (this.options.previewContainer) {
            this.previewContainer = document.querySelector(this.options.previewContainer);
        } else {
            // Create preview container after the file input
            this.previewContainer = document.createElement('div');
            this.previewContainer.className = 'image-preview-container mt-2';
            this.fileInput.parentNode.insertBefore(this.previewContainer, this.fileInput.nextSibling);
        }
    }
    
    bindEvents() {
        this.fileInput.addEventListener('change', (e) => {
            this.handleFileSelect(e);
        });
        
        // Drag and drop support
        this.fileInput.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.fileInput.classList.add('border-blue-400', 'bg-blue-50');
        });
        
        this.fileInput.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.fileInput.classList.remove('border-blue-400', 'bg-blue-50');
        });
        
        this.fileInput.addEventListener('drop', (e) => {
            e.preventDefault();
            this.fileInput.classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = Array.from(e.dataTransfer.files);
            this.processFiles(files);
        });
    }
    
    handleFileSelect(event) {
        const files = Array.from(event.target.files);
        this.processFiles(files);
    }
    
    processFiles(files) {
        if (!this.options.multiple) {
            // Clear existing previews for single file upload
            this.clearPreviews();
        }
        
        files.forEach(file => {
            if (this.validateFile(file)) {
                this.createPreview(file);
            }
        });
    }
    
    validateFile(file) {
        // Check file type
        if (!this.options.allowedTypes.includes(file.type)) {
            this.showError(`Invalid file type: ${file.type}. Allowed types: ${this.options.allowedTypes.join(', ')}`);
            return false;
        }
        
        // Check file size
        if (file.size > this.options.maxFileSize) {
            const maxSizeMB = (this.options.maxFileSize / 1024 / 1024).toFixed(2);
            this.showError(`File size too large: ${this.formatFileSize(file.size)}. Maximum allowed: ${maxSizeMB}MB`);
            return false;
        }
        
        return true;
    }
    
    createPreview(file) {
        const previewId = this.generateId();
        const reader = new FileReader();
        
        reader.onload = (e) => {
            const previewElement = this.createPreviewElement(e.target.result, file, previewId);
            this.previewContainer.appendChild(previewElement);
            this.previews.push({ id: previewId, file, element: previewElement });
        };
        
        reader.onerror = () => {
            this.showError('Error reading file: ' + file.name);
        };
        
        reader.readAsDataURL(file);
    }
    
    createPreviewElement(imageSrc, file, previewId) {
        const preview = document.createElement('div');
        preview.className = 'image-preview-item relative inline-block mr-2 mb-2';
        preview.setAttribute('data-preview-id', previewId);
        
        const imageContainer = document.createElement('div');
        imageContainer.className = 'relative group';
        
        const image = document.createElement('img');
        image.src = imageSrc;
        image.className = 'h-24 w-24 object-cover rounded-lg border border-gray-300 shadow-sm';
        image.alt = file.name;
        
        const overlay = document.createElement('div');
        overlay.className = 'absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center';
        
        if (this.options.showRemoveButton) {
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-white hover:text-red-300 transition-colors duration-200';
            removeButton.innerHTML = `
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            `;
            removeButton.addEventListener('click', () => {
                this.removePreview(previewId);
            });
            
            overlay.appendChild(removeButton);
        }
        
        imageContainer.appendChild(image);
        imageContainer.appendChild(overlay);
        preview.appendChild(imageContainer);
        
        // Add file info if enabled
        if (this.options.showFileName || this.options.showFileSize) {
            const fileInfo = document.createElement('div');
            fileInfo.className = 'mt-1 text-xs text-gray-600';
            
            if (this.options.showFileName) {
                const fileName = document.createElement('div');
                fileName.className = 'truncate max-w-24';
                fileName.textContent = file.name;
                fileName.title = file.name;
                fileInfo.appendChild(fileName);
            }
            
            if (this.options.showFileSize) {
                const fileSize = document.createElement('div');
                fileSize.className = 'text-gray-500';
                fileSize.textContent = this.formatFileSize(file.size);
                fileInfo.appendChild(fileSize);
            }
            
            preview.appendChild(fileInfo);
        }
        
        return preview;
    }
    
    removePreview(previewId) {
        const previewIndex = this.previews.findIndex(p => p.id === previewId);
        if (previewIndex === -1) return;
        
        const preview = this.previews[previewIndex];
        
        // Remove from DOM
        if (preview.element.parentNode) {
            preview.element.parentNode.removeChild(preview.element);
        }
        
        // Remove from array
        this.previews.splice(previewIndex, 1);
        
        // Update file input
        this.updateFileInput();
        
        // Show success message
        if (window.Toast) {
            window.Toast.success('Image removed successfully');
        }
    }
    
    clearPreviews() {
        this.previews.forEach(preview => {
            if (preview.element.parentNode) {
                preview.element.parentNode.removeChild(preview.element);
            }
        });
        this.previews = [];
    }
    
    updateFileInput() {
        if (this.previews.length === 0) {
            this.fileInput.value = '';
            return;
        }
        
        // Create new FileList with remaining files
        const dt = new DataTransfer();
        this.previews.forEach(preview => {
            dt.items.add(preview.file);
        });
        this.fileInput.files = dt.files;
    }
    
    checkExistingImage() {
        // Check if there's an existing image URL in a data attribute
        const existingImageUrl = this.fileInput.dataset.existingImage;
        if (existingImageUrl) {
            this.showExistingImage(existingImageUrl);
        }
    }
    
    showExistingImage(imageUrl) {
        const preview = document.createElement('div');
        preview.className = 'image-preview-item relative inline-block mr-2 mb-2';
        preview.setAttribute('data-existing-image', 'true');
        
        const imageContainer = document.createElement('div');
        imageContainer.className = 'relative group';
        
        const image = document.createElement('img');
        image.src = imageUrl;
        image.className = 'h-24 w-24 object-cover rounded-lg border border-gray-300 shadow-sm';
        image.alt = 'Current image';
        
        const overlay = document.createElement('div');
        overlay.className = 'absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center';
        
        const label = document.createElement('div');
        label.className = 'absolute -top-2 -left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded';
        label.textContent = 'Current';
        
        if (this.options.showRemoveButton) {
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-white hover:text-red-300 transition-colors duration-200';
            removeButton.innerHTML = `
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            `;
            removeButton.addEventListener('click', () => {
                this.removeExistingImage(preview);
            });
            
            overlay.appendChild(removeButton);
        }
        
        imageContainer.appendChild(image);
        imageContainer.appendChild(overlay);
        imageContainer.appendChild(label);
        preview.appendChild(imageContainer);
        
        this.previewContainer.appendChild(preview);
    }
    
    removeExistingImage(previewElement) {
        if (confirm('Are you sure you want to remove the current image?')) {
            previewElement.remove();
            
            // Add a hidden input to indicate image removal
            const removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_image';
            removeInput.value = '1';
            this.fileInput.parentNode.appendChild(removeInput);
            
            if (window.Toast) {
                window.Toast.success('Current image will be removed when you save');
            }
        }
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    generateId() {
        return 'preview-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    }
    
    showError(message) {
        if (window.Toast) {
            window.Toast.error(message);
        } else {
            alert(message);
        }
    }
    
    // Public methods
    getFiles() {
        return this.previews.map(p => p.file);
    }
    
    hasFiles() {
        return this.previews.length > 0;
    }
    
    reset() {
        this.clearPreviews();
        this.fileInput.value = '';
    }
}

// Auto-initialize for file inputs with data-image-preview attribute
document.addEventListener('DOMContentLoaded', () => {
    const fileInputs = document.querySelectorAll('input[type="file"][data-image-preview]');
    fileInputs.forEach(input => {
        const options = {};
        
        // Parse options from data attributes
        if (input.dataset.maxSize) {
            options.maxFileSize = parseInt(input.dataset.maxSize) * 1024; // Convert KB to bytes
        }
        
        if (input.dataset.allowedTypes) {
            options.allowedTypes = input.dataset.allowedTypes.split(',').map(type => type.trim());
        }
        
        if (input.dataset.previewContainer) {
            options.previewContainer = input.dataset.previewContainer;
        }
        
        options.multiple = input.hasAttribute('multiple');
        options.showFileName = input.dataset.showFileName !== 'false';
        options.showFileSize = input.dataset.showFileSize !== 'false';
        options.showRemoveButton = input.dataset.showRemoveButton !== 'false';
        
        new ImagePreview(input, options);
    });
});

// Export for manual initialization
window.ImagePreview = ImagePreview;
export default ImagePreview;