/**
 * Dashboard Charts Initialization
 * Simplified version untuk handle chart rendering dengan baik
 */

export class DashboardChartsManager {
    constructor() {
        this.charts = {};
        this.chartElements = {
            revenue: 'revenueChart',
            topProducts: 'topProductsChart',
            quickStats: 'quickStatsChart'
        };
    }

    /**
     * Initialize dashboard when DOM is ready
     */
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.initRevenueChart();
            this.initTopProductsChart();
        });
    }

    /**
     * Initialize Revenue Chart
     */
    initRevenueChart() {
        const chartId = this.chartElements.revenue;
        const canvas = document.getElementById(chartId);
        
        if (!canvas) {
            console.log(`Chart element ${chartId} not found`);
            return;
        }

        try {
            const ctx = canvas.getContext('2d');
            const revenueData = window.chartDataGlobal?.revenue || [];

            if (!revenueData || revenueData.length === 0) {
                console.warn('Revenue data is empty');
                return;
            }

            // Destroy existing chart if any
            if (this.charts.revenue) {
                this.charts.revenue.destroy();
            }

            this.charts.revenue = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => item.label || item.date || 'N/A'),
                    datasets: [{
                        label: 'Revenue',
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
     * Initialize Top Products Chart
     */
    initTopProductsChart() {
        const chartId = this.chartElements.topProducts;
        const canvas = document.getElementById(chartId);
        
        if (!canvas) {
            console.log(`Chart element ${chartId} not found`);
            return;
        }

        try {
            const ctx = canvas.getContext('2d');
            const productsData = window.chartDataGlobal?.topProducts || [];

            if (!productsData || productsData.length === 0) {
                console.warn('Top products data is empty');
                return;
            }

            // Destroy existing chart if any
            if (this.charts.topProducts) {
                this.charts.topProducts.destroy();
            }

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
     * Update chart with new data
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
     * Destroy all charts
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

// Initialize when document loads
export default new DashboardChartsManager();