/**
 * Dashboard Charts Initialization
 * Class ini bertanggung jawab untuk menginisialisasi
 * dan mengelola seluruh chart pada halaman dashboard.
 */

export class DashboardChartsManager {
    constructor() {
        // Menyimpan instance chart yang aktif
        this.charts = {};

        // Mapping ID elemen canvas untuk setiap chart
        this.chartElements = {
            revenue: 'revenueChart',
            topProducts: 'topProductsChart',
            quickStats: 'quickStatsChart'
        };
    }

    /**
     * Inisialisasi dashboard setelah DOM selesai dimuat
     * Memanggil fungsi pembuatan chart utama
     */
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.initRevenueChart();
            this.initTopProductsChart();
        });
    }

    /**
     * Inisialisasi Revenue Chart (Line Chart)
     * Digunakan untuk menampilkan data pendapatan
     */
    initRevenueChart() {
        const chartId = this.chartElements.revenue;
        const canvas = document.getElementById(chartId);
        
        // Jika elemen canvas tidak ditemukan, hentikan proses
        if (!canvas) {
            console.log(`Chart element ${chartId} not found`);
            return;
        }

        try {
            const ctx = canvas.getContext('2d');

            // Mengambil data revenue dari variabel global
            const revenueData = window.chartDataGlobal?.revenue || [];

            // Validasi data
            if (!revenueData || revenueData.length === 0) {
                console.warn('Revenue data is empty');
                return;
            }

            // Hapus chart lama jika sudah ada
            if (this.charts.revenue) {
                this.charts.revenue.destroy();
            }

            // Membuat chart revenue baru
            this.charts.revenue = new Chart(ctx, {
                type: 'line',
                data: {
                    // Label sumbu X
                    labels: revenueData.map(item => item.label || item.date || 'N/A'),
                    datasets: [{
                        label: 'Revenue',
                        // Data nilai revenue
                        data: revenueData.map(item => item.value || item.revenue || 0),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#1E40AF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleColor: '#FFFFFF',
                            bodyColor: '#FFFFFF',
                            borderColor: '#3B82F6',
                            borderWidth: 1,
                            callbacks: {
                                // Format tooltip value ke Rupiah
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                },
                                title: function(tooltipItems) {
                                    return tooltipItems[0].label || '';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format angka sumbu Y ke Rupiah
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    }
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                },
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            console.log('Revenue chart initialized successfully');
        } catch (error) {
            console.error('Error initializing revenue chart:', error);
        }
    }

    /**
     * Inisialisasi Top Products Chart (Doughnut Chart)
     * Menampilkan produk terlaris
     */
    initTopProductsChart() {
        const chartId = this.chartElements.topProducts;
        const canvas = document.getElementById(chartId);
        
        // Validasi elemen canvas
        if (!canvas) {
            console.log(`Chart element ${chartId} not found`);
            return;
        }

        try {
            const ctx = canvas.getContext('2d');

            // Mengambil data produk terlaris
            const productsData = window.chartDataGlobal?.topProducts || [];

            // Validasi data
            if (!productsData || productsData.length === 0) {
                console.warn('Top products data is empty');
                return;
            }

            // Hapus chart lama jika ada
            if (this.charts.topProducts) {
                this.charts.topProducts.destroy();
            }

            // Membuat chart doughnut
            this.charts.topProducts = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: productsData.map(item => item.name || 'N/A'),
                    datasets: [{
                        data: productsData.map(item => item.sold || item.total_sold || 0),
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#8B5CF6',
                            '#06B6D4',
                            '#84CC16',
                            '#F97316'
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                // Tooltip jumlah penjualan
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return label + ': ' + value + ' sold';
                                }
                            }
                        }
                    }
                }
            });

            console.log('Top products chart initialized successfully');
        } catch (error) {
            console.error('Error initializing top products chart:', error);
        }
    }

    /**
     * Update data chart tertentu tanpa membuat ulang chart
     * @param {string} chartType - tipe chart (revenue, topProducts)
     * @param {Array} newData - data baru
     */
    updateChart(chartType, newData) {
        const chart = this.charts[chartType];
        if (!chart) {
            console.warn(`Chart ${chartType} not found`);
            return;
        }

        chart.data.datasets[0].data = newData;
        chart.update();
    }

    /**
     * Menghapus seluruh chart yang aktif
     * Digunakan saat reload atau cleanup
     */
    destroyAll() {
        Object.values(this.charts).forEach(chart => {
            if (chart) {
                chart.destroy();
            }
        });
        this.charts = {};
    }
}

// Mengekspor instance DashboardChartsManager
// agar bisa langsung digunakan
export default new DashboardChartsManager();
