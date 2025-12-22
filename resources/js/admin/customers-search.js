/**
 * Customers Live Search & Filter
 * Handles real-time search and filtering for customers admin page
 */

class CustomersSearch {
    constructor() {
        this.searchInput = document.getElementById('customers-search');
        this.filterForm = document.getElementById('customers-filter-form');
        this.resultsContainer = document.getElementById('customers-results');
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
            const filterInputs = this.filterForm.querySelectorAll('select, input[type="number"], input[type="date"]');
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
                per_page: 15
            });
            
            const response = await fetch(`/admin/customers/search/api?${params}`, {
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
            
            const response = await fetch(`/admin/customers/filter?${params}`, {
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
    
    displayResults(customers, pagination) {
        if (!this.resultsContainer) return;
        
        let html = '';
        
        if (customers.length === 0) {
            html = this.getEmptyStateHTML();
        } else {
            html = customers.map(customer => this.getCustomerCardHTML(customer)).join('');
        }
        
        this.resultsContainer.innerHTML = html;
        
        // Update pagination
        this.updatePagination(pagination);
        
        // Bind new event listeners
        this.bindResultActions();
    }
    
    getCustomerCardHTML(customer) {
        const totalSpent = customer.transactions_sum_total_amount || 0;
        const transactionCount = customer.transactions_count || 0;
        const pointsClass = customer.points > 100 ? 'text-green-600' : customer.points > 50 ? 'text-yellow-600' : 'text-gray-600';
        
        return `
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <a href="/admin/customers/${customer.id}" class="hover:text-blue-600">
                                            ${customer.name}
                                        </a>
                                    </h3>
                                    <div class="mt-1 space-y-1">
                                        ${customer.phone ? `<p class="text-sm text-gray-500"><span class="font-medium">Phone:</span> ${customer.phone}</p>` : ''}
                                        ${customer.email ? `<p class="text-sm text-gray-500"><span class="font-medium">Email:</span> ${customer.email}</p>` : ''}
                                        ${customer.address ? `<p class="text-sm text-gray-500"><span class="font-medium">Address:</span> ${customer.address}</p>` : ''}
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${transactionCount} transactions
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">Spent: Rp ${this.formatPrice(totalSpent)}</span>
                                    <span class="text-xs ${pointsClass}">Points: ${customer.points}</span>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center space-x-2">
                                <a href="/admin/customers/${customer.id}/edit" 
                                   class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Edit
                                </a>
                                <a href="/admin/customers/${customer.id}" 
                                   class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                    View Details
                                </a>
                                <button type="button" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200"
                                        onclick="updatePoints(${customer.id}, ${customer.points})">
                                    Update Points
                                </button>
                                <button type="button" 
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200"
                                        onclick="deleteCustomer(${customer.id})">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No customers found</h3>
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
                const customerId = button.dataset.customerId;
                this.handleResultAction(action, customerId);
            });
        });
    }
    
    handleResultAction(action, customerId) {
        switch (action) {
            case 'delete':
                this.deleteCustomer(customerId);
                break;
            case 'update-points':
                this.updatePoints(customerId);
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
            countElement.textContent = `${total} customers found`;
        }
    }
    
    updateActiveFilters(filters) {
        const activeFiltersContainer = document.getElementById('active-filters');
        if (!activeFiltersContainer) return;
        
        let html = '';
        const filterLabels = {
            points_min: 'Min Points',
            points_max: 'Max Points',
            date_from: 'From Date',
            date_to: 'To Date',
            min_transactions: 'Min Transactions'
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
    if (document.getElementById('customers-search')) {
        new CustomersSearch();
    }
});

// Global functions for inline event handlers
window.clearAllFilters = function() {
    const form = document.getElementById('customers-filter-form');
    if (form) {
        form.reset();
        // Trigger filter update
        const searchInstance = window.customersSearchInstance;
        if (searchInstance) {
            searchInstance.handleFilter();
        }
    }
};

window.removeFilter = function(filterKey) {
    const input = document.querySelector(`[name="${filterKey}"]`);
    if (input) {
        input.value = '';
        // Trigger filter update
        const searchInstance = window.customersSearchInstance;
        if (searchInstance) {
            searchInstance.handleFilter();
        }
    }
};

window.deleteCustomer = function(customerId) {
    if (confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
        // Implement delete functionality
        console.log('Delete customer:', customerId);
    }
};

window.updatePoints = function(customerId, currentPoints) {
    const newPoints = prompt(`Update points for customer (current: ${currentPoints}):`, currentPoints);
    if (newPoints !== null && !isNaN(newPoints)) {
        // Implement update points functionality
        console.log('Update points for customer:', customerId, 'to:', newPoints);
    }
};