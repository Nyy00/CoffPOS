/**
 * Products Live Search & Filter
 * Class ini menangani:
 * - Live search produk (real-time)
 * - Filter (kategori, stok, harga, dll)
 * - Sorting
 * - Pagination
 * Untuk halaman admin products
 */

class ProductsSearch {
    constructor() {
        // Ambil elemen-elemen penting dari DOM
        this.searchInput = document.getElementById('products-search');
        this.filterForm = document.getElementById('products-filter-form');
        this.resultsContainer = document.getElementById('products-results');
        this.loadingIndicator = document.getElementById('search-loading');

        // Variabel bantu
        this.searchTimeout = null; // untuk debounce search
        this.currentPage = 1;
        this.isLoading = false; // mencegah request dobel

        // Inisialisasi semua event
        this.init();
    }

    /**
     * Inisialisasi event listener utama
     */
    init() {
        if (!this.searchInput) return;

        // Event input search (live search)
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearch(e.target.value);
        });

        // Event perubahan filter (checkbox, radio, select)
        if (this.filterForm) {
            const filterInputs = this.filterForm.querySelectorAll(
                'select, input[type="checkbox"], input[type="radio"]'
            );

            filterInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.handleFilter();
                });
            });
        }

        // Binding fitur tambahan
        this.bindSortControls();
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
     * Handle input search dengan debounce
     */
    handleSearch(query) {
        // Hapus timeout sebelumnya
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        // Delay 300ms supaya tidak request terus-menerus
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }

    /**
     * Request search ke backend (API)
     */
    async performSearch(query) {
        if (this.isLoading) return;

        this.showLoading();
        this.isLoading = true;

        try {
            // Parameter search
            const params = new URLSearchParams({
                q: query,
                per_page: 15,
                available_only: document.getElementById('available-only')?.checked || false
            });

            // Fetch data search
            const response = await fetch(`/admin/products/search/api?${params}`, {
                method: 'GET',
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

            // Jika sukses, tampilkan hasil
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
     * Handle filter produk
     */
    async handleFilter() {
        if (this.isLoading) return;

        this.showLoading();
        this.isLoading = true;

        try {
            const formData = new FormData(this.filterForm);
            const params = new URLSearchParams();

            // Tambahkan search jika ada
            if (this.searchInput.value) {
                params.append('search', this.searchInput.value);
            }

            // Ambil semua input filter
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
     * Menampilkan hasil produk ke halaman
     */
    displayResults(products, pagination) {
        if (!this.resultsContainer) return;

        let html = '';

        // Jika data kosong
        if (products.length === 0) {
            html = this.getEmptyStateHTML();
        } else {
            html = products
                .map(product => this.getProductCardHTML(product))
                .join('');
        }

        this.resultsContainer.innerHTML = html;

        // Update pagination
        this.updatePagination(pagination);

        // Re-bind action button (edit, delete, dll)
        this.bindResultActions();
    }

    /**
     * Format harga ke format Indonesia
     */
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }
}

/**
 * Inisialisasi class saat DOM siap
 */
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('products-search')) {
        window.productsSearchInstance = new ProductsSearch();
    }
});

/**
 * Fungsi global untuk event inline HTML
 */

// Reset semua filter
window.clearAllFilters = function() {
    const form = document.getElementById('products-filter-form');
    if (form) {
        form.reset();
        window.productsSearchInstance?.handleFilter();
    }
};

// Hapus satu filter
window.removeFilter = function(filterKey) {
    const input = document.querySelector(`[name="${filterKey}"]`);
    if (input) {
        input.type === 'checkbox' ? input.checked = false : input.value = '';
        window.productsSearchInstance?.handleFilter();
    }
};

// Delete produk (dummy)
window.deleteProduct = function(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        console.log('Delete product:', productId);
    }
};
