/**
 * Dashboard Charts Management
 * Mengelola seluruh chart pada dashboard menggunakan Chart.js
 * Termasuk inisialisasi, update data real-time, dan auto refresh
 */

class DashboardCharts {
    constructor() {
        // Menyimpan semua instance chart
        this.charts = {};

        // Interval auto refresh chart
        this.refreshInterval = null;

        // Waktu refresh (30 detik)
        this.refreshRate = 30000;
        
        // Inisialisasi dashboard chart
        this.init();
    }
    
    async init() {
        // Pastikan Chart.js sudah ter-load
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }
        
        // Setup default tampilan chart
        this.setupChartDefaults();

        // Inisialisasi semua chart
        await this.initializeCharts();

        // Binding event (filter, refresh, dll)
        this.bindEvents();

        // Mulai auto refresh
        this.startAutoRefresh();
    }
    
    setupChartDefaults() {
        // Pengaturan global Chart.js
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#6B7280';
        Chart.defaults.plugins.legend.display = true;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        Chart.defaults.plugins.tooltip.titleColor = '#FFFFFF';
        Chart.defaults.plugins.tooltip.bodyColor = '#FFFFFF';
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
    }
    
    async initializeCharts() {
        // Inisialisasi seluruh chart secara paralel
        await Promise.all([
            this.initRevenueChart(),
            this.initSalesChart(),
            this.initTopProductsChart(),
            this.initPaymentMethodsChart(),
            this.initHourlySalesChart(),
            this.initCustomerGrowthChart()
        ]);
    }
    
    async initRevenueChart() {
        // Chart pendapatan
        const canvas = document.getElementById('revenue-chart');
        if (!canvas) return;
        
        try {
            const data = await this.fetchChartData('revenue', '7days');
            
            this.charts.revenue = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: data.map(item => item.label),
                    datasets: [{
                        label: 'Revenue',
                        data: data.map(item => item.value),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Revenue Trend (7 Days)',
                            font: { size: 16, weight: 'bold' }
                        },
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format ke Rupiah
                                callback: value =>
                                    'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.1)' }
                        },
                        x: { grid: { display: false } }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        } catch (error) {
            console.error('Failed to initialize revenue chart:', error);
        }
    }
    
    async initSalesChart() {
        // Chart transaksi harian
        const canvas = document.getElementById('sales-chart');
        if (!canvas) return;
        
        try {
            const data = await this.fetchChartData('sales');
            
            this.charts.sales = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'Transactions',
                        data: data.map(item => item.transactions),
                        backgroundColor: '#10B981',
                        borderColor: '#059669',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Daily Transactions (30 Days)',
                            font: { size: 16, weight: 'bold' }
                        },
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: 'rgba(0, 0, 0, 0.1)' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        } catch (error) {
            console.error('Failed to initialize sales chart:', error);
        }
    }
    
    async initTopProductsChart() {
        // Chart produk terlaris
        const canvas = document.getElementById('top-products-chart');
        if (!canvas) return;
        
        try {
            const data = await this.fetchChartData('products');
            
            this.charts.topProducts = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.name),
                    datasets: [{
                        data: data.map(item => item.sold),
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Top Products (This Month)',
                            font: { size: 16, weight: 'bold' }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                // Menampilkan persentase penjualan
                                label: context => {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percent = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Failed to initialize top products chart:', error);
        }
    }

    // ====== Fungsi lain tetap sama, hanya diberi komentar ======

    /**
     * Mengambil data chart dari backend
     */
    async fetchChartData(type, period = null) {
        try {
            const params = new URLSearchParams();
            if (period) params.append('period', period);
            
            const response = await fetch(`/admin/dashboard/charts/${type}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            });
            
            const result = await response.json();
            return result.success ? result.data : [];
            
        } catch (error) {
            console.error(`Failed to fetch ${type} chart data:`, error);
            return [];
        }
    }
}

/**
 * Inisialisasi dashboard chart setelah DOM siap
 */
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('revenue-chart')) {
        const initCharts = () => {
            if (typeof Chart !== 'undefined') {
                window.DashboardCharts = new DashboardCharts();
            } else {
                setTimeout(initCharts, 100);
            }
        };
        initCharts();
    }
});

/**
 * Cleanup saat halaman ditutup
 */
window.addEventListener('beforeunload', () => {
    if (window.DashboardCharts) {
        window.DashboardCharts.stopAutoRefresh();
        window.DashboardCharts.destroyAllCharts();
    }
});

export default DashboardCharts;