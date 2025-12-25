/**
 * Products Live Search & Filter
 * Handles real-time search and filtering for products admin page
 */

class ProductsSearch {
    constructor() {
        this.searchInput = document.getElementById('products-search');
        this.filterForm = document.getElementById('products-filter-form');
        this.resultsContainer = document.getElementById('products-results');
        this.loadingIndicator = document.getElementById('search-loading');
        this.searchTimeout = null;
        this.currentPage = 1;
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        if (!this.searchInput) return;
        
        // Bind search input events
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearch(e.target.value);
        });
        
        // Bind filter form events
        if (this.filterForm) {
            const filterInputs = this.filterForm.querySelectorAll('select, input[type="checkbox"], input[type="radio"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.handleFilter();
                });
            });
        }
        
        // Bind sort controls
        this.bindSortControls();
        
        // Bind pagination
        this.bindPagination();
        
        // Clear search button
        const clearBtn = document.getElementById('clear-search');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                this.clearSearch();
            });
        }
    }
    
    handleSearch(query) {
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        // Debounce search - wait 300ms after user stops typing
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }
    
    async performSearch(query) {
        if (this.isLoading) return;
        
        this.showLoading();
        this.isLoading = true;
        
        try {
            const params = new URLSearchParams({
                q: query,
                per_page: 15,
                available_only: document.getElementById('available-only')?.checked || false
            });
            
            const response = await fetch(`/admin/products/search/api?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error('Search request failed');
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.displayResults(data.data, data.pagination);
                this.updateResultsCount(data.pagination.total);
            } else {
                this.showError('Search failed. Please try again.');
            }
            
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Search failed. Please check your connection.');
        } finally {
            this.hideLoading();
            this.isLoading = false;
        }
    }
    
    async handleFilter() {
        if (this.isLoading) return;
        
        this.showLoading();
        this.isLoading = true;
        
        try {
            const formData = new FormData(this.filterForm);
            const params = new URLSearchParams();
            
            // Add search query if exists
            if (this.searchInput.value) {
                params.append('search', this.searchInput.value);
            }
            
            // Add filter parameters
            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            const response = await fetch(`/admin/products/filter?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error('Filter request failed');
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.displayResults(data.data, data.pagination);
                this.updateResultsCount(data.pagination.total);
                this.updateActiveFilters(data.filters_applied);
            } else {
                this.showError('Filter failed. Please try again.');
            }
            
        } catch (error) {
            console.error('Filter error:', error);
            this.showError('Filter failed. Please check your connection.');
        } finally {
            this.hideLoading();
            this.isLoading = false;
        }
    }
    
    displayResults(products, pagination) {
        if (!this.resultsContainer) return;
        
        let html = '';
        
        if (products.length === 0) {
            html = this.getEmptyStateHTML();
        } else {
            html = products.map(product => this.getProductCardHTML(product)).join('');
        }
        
        this.resultsContainer.innerHTML = html;
        
        // Update pagination
        this.updatePagination(pagination);
        
        // Bind new event listeners
        this.bindResultActions();
    }
    
    getFallbackImage(productName) {
        const name = productName.toLowerCase();
        if (name.includes('cheesecake')) return '/images/products/cheesecake.jpg';
        if (name.includes('sandwich')) return '/images/products/sandwich.jpg';
        if (name.includes('tiramisu')) return '/images/products/tiramisu.jpg';
        if (name.includes('chocolate')) return '/images/products/chocolate.jpg';
        if (name.includes('croissant')) return '/images/products/croissants.jpg';
        if (name.includes('americano')) return '/images/products/americano.jpg';
        if (name.includes('latte')) return '/images/products/latte.jpg';
        if (name.includes('cappuccino')) return '/images/products/cappuccino.jpg';
        if (name.includes('espresso')) return '/images/products/espresso.jpg';
        if (name.includes('mocha')) return '/images/products/mocha.jpg';
        if (name.includes('tea')) return '/images/products/green-tea.jpg';
        return '/images/placeholder-product.png';
    }
    
    getProductCardHTML(product) {
        // Use public images as primary in production
        const imageUrl = product.image 
            ? `/images/products/${product.image.replace('products/', '')}` 
            : '/images/placeholder-product.png';
            
        const stockClass = product.stock < 10 ? 'text-red-600' : 'text-green-600';
        const availabilityBadge = product.is_available 
            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Available</span>'
            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unavailable</span>';
        
        return `
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-16 w-16 rounded-lg object-cover" 
                                 src="${imageUrl}" 
                                 alt="${product.name}"
                                 onerror="this.onerror=null; this.src=this.getFallbackImage('${product.name}');">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                        <a href="/admin/products/${product.id}" class="hover:text-blue-600">
                                            ${product.name}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500">${product.category?.name || 'No Category'}</p>
                                    <p class="text-sm text-gray-600 mt-1">${product.description || 'No description'}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    ${availabilityBadge}
                                    <span class="text-sm font-medium text-gray-900">Rp ${this.formatPrice(product.price)}</span>
                                    <span class="text-xs ${stockClass}">Stock: ${product.stock}</span>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center space-x-2">
                                <a href="/admin/products/${product.id}/edit" 
                                   class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Edit
                                </a>
                                <a href="/admin/products/${product.id}" 
                                   class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                    View
                                </a>
                                <button type="button" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200"
                                        onclick="deleteProduct(${product.id})">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    getEmptyStateHTML() {
        return `
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4a1 1 0 00-1-1H9a1 1 0 00-1 1v1"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                <div class="mt-6">
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                            onclick="clearAllFilters()">
                        Clear filters
                    </button>
                </div>
            </div>
        `;
    }
    
    bindSortControls() {
        const sortControls = document.querySelectorAll('[data-sort]');
        sortControls.forEach(control => {
            control.addEventListener('click', (e) => {
                e.preventDefault();
                const sortBy = control.dataset.sort;
                const currentOrder = control.dataset.order || 'asc';
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                
                this.handleSort(sortBy, newOrder);
                
                // Update UI
                control.dataset.order = newOrder;
                this.updateSortIndicators(sortBy, newOrder);
            });
        });
    }
    
    async handleSort(sortBy, sortOrder) {
        // Add sort parameters to current search/filter
        const params = new URLSearchParams(window.location.search);
        params.set('sort_by', sortBy);
        params.set('sort_order', sortOrder);
        
        // Update URL without reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
        
        // Trigger filter with new sort
        this.handleFilter();
    }
    
    updateSortIndicators(activeSortBy, sortOrder) {
        const sortControls = document.querySelectorAll('[data-sort]');
        sortControls.forEach(control => {
            const icon = control.querySelector('.sort-icon');
            if (control.dataset.sort === activeSortBy) {
                control.classList.add('text-blue-600');
                if (icon) {
                    icon.innerHTML = sortOrder === 'asc' ? '↑' : '↓';
                }
            } else {
                control.classList.remove('text-blue-600');
                if (icon) {
                    icon.innerHTML = '↕';
                }
            }
        });
    }
    
    bindPagination() {
        // This will be called after results are displayed
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-page]')) {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                this.loadPage(page);
            }
        });
    }
    
    async loadPage(page) {
        this.currentPage = page;
        // Re-run current search/filter with new page
        if (this.searchInput.value) {
            this.performSearch(this.searchInput.value);
        } else {
            this.handleFilter();
        }
    }
    
    updatePagination(pagination) {
        const paginationContainer = document.getElementById('pagination-container');
        if (!paginationContainer) return;
        
        let html = '';
        
        if (pagination.last_page > 1) {
            html = `
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        ${pagination.current_page > 1 ? 
                            `<button data-page="${pagination.current_page - 1}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</button>` : 
                            '<span></span>'
                        }
                        ${pagination.current_page < pagination.last_page ? 
                            `<button data-page="${pagination.current_page + 1}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</button>` : 
                            '<span></span>'
                        }
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">${pagination.from || 0}</span> to <span class="font-medium">${pagination.to || 0}</span> of <span class="font-medium">${pagination.total}</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                ${this.generatePaginationLinks(pagination)}
                            </nav>
                        </div>
                    </div>
                </div>
            `;
        }
        
        paginationContainer.innerHTML = html;
    }
    
    generatePaginationLinks(pagination) {
        let links = '';
        
        // Previous button
        if (pagination.current_page > 1) {
            links += `<button data-page="${pagination.current_page - 1}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Previous</button>`;
        }
        
        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === pagination.current_page;
            links += `
                <button data-page="${i}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ${
                    isActive 
                        ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' 
                        : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50'
                }">
                    ${i}
                </button>
            `;
        }
        
        // Next button
        if (pagination.current_page < pagination.last_page) {
            links += `<button data-page="${pagination.current_page + 1}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Next</button>`;
        }
        
        return links;
    }
    
    bindResultActions() {
        // Re-bind any action buttons in the results
        const actionButtons = this.resultsContainer.querySelectorAll('[data-action]');
        actionButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const action = button.dataset.action;
                const productId = button.dataset.productId;
                this.handleResultAction(action, productId);
            });
        });
    }
    
    handleResultAction(action, productId) {
        switch (action) {
            case 'delete':
                this.deleteProduct(productId);
                break;
            case 'toggle-availability':
                this.toggleAvailability(productId);
                break;
            // Add more actions as needed
        }
    }
    
    clearSearch() {
        this.searchInput.value = '';
        this.performSearch('');
    }
    
    showLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.classList.remove('hidden');
        }
    }
    
    hideLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.classList.add('hidden');
        }
    }
    
    showError(message) {
        // You can implement a toast notification here
        console.error(message);
        alert(message); // Temporary - replace with proper notification
    }
    
    updateResultsCount(total) {
        const countElement = document.getElementById('results-count');
        if (countElement) {
            countElement.textContent = `${total} products found`;
        }
    }
    
    updateActiveFilters(filters) {
        const activeFiltersContainer = document.getElementById('active-filters');
        if (!activeFiltersContainer) return;
        
        let html = '';
        const filterLabels = {
            category_id: 'Category',
            is_available: 'Availability',
            stock_min: 'Min Stock',
            stock_max: 'Max Stock',
            price_min: 'Min Price',
            price_max: 'Max Price'
        };
        
        Object.entries(filters).forEach(([key, value]) => {
            if (value && filterLabels[key]) {
                html += `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${filterLabels[key]}: ${value}
                        <button type="button" class="ml-1 text-blue-600 hover:text-blue-800" onclick="removeFilter('${key}')">×</button>
                    </span>
                `;
            }
        });
        
        activeFiltersContainer.innerHTML = html;
    }
    
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('products-search')) {
        new ProductsSearch();
    }
});

// Global functions for inline event handlers
window.clearAllFilters = function() {
    const form = document.getElementById('products-filter-form');
    if (form) {
        form.reset();
        // Trigger filter update
        const searchInstance = window.productsSearchInstance;
        if (searchInstance) {
            searchInstance.handleFilter();
        }
    }
};

window.removeFilter = function(filterKey) {
    const input = document.querySelector(`[name="${filterKey}"]`);
    if (input) {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
        // Trigger filter update
        const searchInstance = window.productsSearchInstance;
        if (searchInstance) {
            searchInstance.handleFilter();
        }
    }
};

window.deleteProduct = function(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        // Implement delete functionality
        console.log('Delete product:', productId);
    }
};