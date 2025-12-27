/**
 * Customers Live Search & Filter
 * Mengatur fitur pencarian, filter, sorting, dan pagination
 * pada halaman admin customers secara real-time (AJAX)
 */

class CustomersSearch {
    constructor() {
        // Elemen utama
        this.searchInput = document.getElementById('customers-search');
        this.filterForm = document.getElementById('customers-filter-form');
        this.resultsContainer = document.getElementById('customers-results');
        this.loadingIndicator = document.getElementById('search-loading');

        // State pencarian
        this.searchTimeout = null;
        this.currentPage = 1;
        this.isLoading = false;
        
        // Inisialisasi
        this.init();
    }
    
    init() {
        // Jika input search tidak ada, hentikan
        if (!this.searchInput) return;
        
        // Event input search (live search)
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearch(e.target.value);
        });
        
        // Event filter (select, number, date)
        if (this.filterForm) {
            const filterInputs = this.filterForm.querySelectorAll(
                'select, input[type="number"], input[type="date"]'
            );

            filterInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.handleFilter();
                });
            });
        }
        
        // Sorting
        this.bindSortControls();

        // Pagination
        this.bindPagination();
        
        // Tombol clear search
        const clearBtn = document.getElementById('clear-search');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                this.clearSearch();
            });
        }
    }
    
    /**
     * Menangani input search dengan debounce
     */
    handleSearch(query) {
        // Hapus timeout sebelumnya
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        // Tunggu 300ms sebelum request (debounce)
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }
    
    /**
     * Request pencarian customer ke server
     */
    async performSearch(query) {
        if (this.isLoading) return;
        
        this.showLoading();
        this.isLoading = true;
        
        try {
            const params = new URLSearchParams({
                q: query,
                per_page: 15
            });
            
            const response = await fetch(
                `/admin/customers/search/api?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
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
    
    /**
     * Menangani filter customer
     */
    async handleFilter() {
        if (this.isLoading) return;
        
        this.showLoading();
        this.isLoading = true;
        
        try {
            const formData = new FormData(this.filterForm);
            const params = new URLSearchParams();
            
            // Sertakan keyword search jika ada
            if (this.searchInput.value) {
                params.append('search', this.searchInput.value);
            }
            
            // Ambil semua nilai filter
            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            const response = await fetch(
                `/admin/customers/filter?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
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
    
    /**
     * Menampilkan hasil customer ke halaman
     */
    displayResults(customers, pagination) {
        if (!this.resultsContainer) return;
        
        let html = '';
        
        // Jika tidak ada data
        if (customers.length === 0) {
            html = this.getEmptyStateHTML();
        } else {
            html = customers
                .map(customer => this.getCustomerCardHTML(customer))
                .join('');
        }
        
        this.resultsContainer.innerHTML = html;
        
        // Update pagination
        this.updatePagination(pagination);
    }
    
    /**
     * Template card customer
     */
    getCustomerCardHTML(customer) {
        const totalSpent = customer.transactions_sum_total_amount || 0;
        const transactionCount = customer.transactions_count || 0;

        // Warna poin berdasarkan jumlah
        const pointsClass =
            customer.points > 100 ? 'text-green-600' :
            customer.points > 50 ? 'text-yellow-600' :
            'text-gray-600';
        
        return `
            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition">
                <div class="p-4">
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-sm font-medium">
                                <a href="/admin/customers/${customer.id}">
                                    ${customer.name}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">${customer.email || ''}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs">${transactionCount} transactions</span>
                            <p class="text-sm font-medium">
                                Rp ${this.formatPrice(totalSpent)}
                            </p>
                            <span class="text-xs ${pointsClass}">
                                Points: ${customer.points}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    /**
     * Sorting customer
     */
    bindSortControls() {
        const sortControls = document.querySelectorAll('[data-sort]');
        sortControls.forEach(control => {
            control.addEventListener('click', (e) => {
                e.preventDefault();

                const sortBy = control.dataset.sort;
                const currentOrder = control.dataset.order || 'asc';
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                
                this.handleSort(sortBy, newOrder);
                control.dataset.order = newOrder;
            });
        });
    }
    
    /**
     * Pagination handler
     */
    bindPagination() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-page]')) {
                e.preventDefault();
                this.loadPage(parseInt(e.target.dataset.page));
            }
        });
    }
    
    /**
     * Load halaman pagination
     */
    async loadPage(page) {
        this.currentPage = page;
        if (this.searchInput.value) {
            this.performSearch(this.searchInput.value);
        } else {
            this.handleFilter();
        }
    }
    
    /**
     * Helper format harga
     */
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
}

/**
 * Inisialisasi saat DOM siap
 */
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('customers-search')) {
        window.customersSearchInstance = new CustomersSearch();
    }
});
