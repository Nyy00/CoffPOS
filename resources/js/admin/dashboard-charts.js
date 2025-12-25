/**
 * Dashboard Charts Management
 * Handles Chart.js integration and real-time dashboard updates
 */

class DashboardCharts {
    constructor() {
        this.charts = {};
        this.refreshInterval = null;
        this.refreshRate = 30000; // 30 seconds
        
        this.init();
    }
    
    async init() {
        // Wait for Chart.js to be available
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }
        
        this.setupChartDefaults();
        await this.initializeCharts();
        this.bindEvents();
        this.startAutoRefresh();
    }
    
    setupChartDefaults() {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#6B7280';
        Chart.defaults.plugins.legend.display = true;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        Chart.defaults.plugins.tooltip.titleColor = '#FFFFFF';
        Chart.defaults.plugins.tooltip.bodyColor = '#FFFFFF';
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
    }
    
    async initializeCharts() {
        // Initialize all charts
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
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
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
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
        } catch (error) {
            console.error('Failed to initialize sales chart:', error);
        }
    }
    
    async initTopProductsChart() {
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
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
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
    
    async initPaymentMethodsChart() {
        const canvas = document.getElementById('payment-methods-chart');
        if (!canvas) return;
        
        try {
            const data = await this.fetchChartData('payment-methods');
            
            this.charts.paymentMethods = new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: data.map(item => item.method),
                    datasets: [{
                        data: data.map(item => item.count),
                        backgroundColor: [
                            '#22C55E', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'
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
                            text: 'Payment Methods (This Month)',
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
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} transactions (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
        } catch (error) {
            console.error('Failed to initialize payment methods chart:', error);
        }
    }
    
    async initHourlySalesChart() {
        const canvas = document.getElementById('hourly-sales-chart');
        if (!canvas) return;
        
        try {
            const data = await this.fetchChartData('hourly-sales');
            
            this.charts.hourlySales = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: data.map(item => item.hour),
                    datasets: [{
                        label: 'Transactions',
                        data: data.map(item => item.transactions),
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#F59E0B',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Hourly Sales (Today)',
                            font: { size: 16, weight: 'bold' }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
        } catch (error) {
            console.error('Failed to initialize hourly sales chart:', error);
        }
    }
    
    async initCustomerGrowthChart() {
        const canvas = document.getElementById('customer-growth-chart');
        if (!canvas) return;
        
        try {
            const data = await this.fetchChartData('customer-growth');
            
            this.charts.customerGrowth = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'New Customers',
                        data: data.map(item => item.new_customers),
                        backgroundColor: '#8B5CF6',
                        borderColor: '#7C3AED',
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
                            text: 'Customer Growth (30 Days)',
                            font: { size: 16, weight: 'bold' }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
        } catch (error) {
            console.error('Failed to initialize customer growth chart:', error);
        }
    }
    
    async fetchChartData(type, period = null) {
        try {
            const params = new URLSearchParams();
            if (period) params.append('period', period);
            
            const response = await fetch(`/admin/dashboard/charts/${type}?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                return result.data;
            } else {
                throw new Error('API returned error');
            }
            
        } catch (error) {
            console.error(`Failed to fetch ${type} chart data:`, error);
            return [];
        }
    }
    
    bindEvents() {
        // Period selector changes
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-chart-period]')) {
                const chartType = e.target.dataset.chartType;
                const period = e.target.value;
                this.updateChart(chartType, period);
            }
        });
        
        // Refresh button clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-refresh-chart]')) {
                const chartType = e.target.dataset.refreshChart;
                this.refreshChart(chartType);
            }
            
            if (e.target.matches('#refresh-all-charts')) {
                this.refreshAllCharts();
            }
        });
        
        // Auto-refresh toggle
        const autoRefreshToggle = document.getElementById('auto-refresh-toggle');
        if (autoRefreshToggle) {
            autoRefreshToggle.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.startAutoRefresh();
                } else {
                    this.stopAutoRefresh();
                }
            });
        }
    }
    
    async updateChart(chartType, period) {
        if (!this.charts[chartType]) return;
        
        try {
            const data = await this.fetchChartData(chartType, period);
            const chart = this.charts[chartType];
            
            // Update chart data
            chart.data.labels = data.map(item => item.label || item.date || item.hour);
            chart.data.datasets[0].data = data.map(item => item.value || item.transactions || item.new_customers);
            
            // Update chart
            chart.update('active');
            
        } catch (error) {
            console.error(`Failed to update ${chartType} chart:`, error);
        }
    }
    
    async refreshChart(chartType) {
        if (!this.charts[chartType]) return;
        
        const refreshBtn = document.querySelector(`[data-refresh-chart="${chartType}"]`);
        if (refreshBtn) {
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        }
        
        try {
            const data = await this.fetchChartData(chartType);
            const chart = this.charts[chartType];
            
            // Update chart data based on chart type
            if (chartType === 'revenue' || chartType === 'hourlySales') {
                chart.data.labels = data.map(item => item.label || item.hour);
                chart.data.datasets[0].data = data.map(item => item.value || item.transactions);
            } else if (chartType === 'sales' || chartType === 'customerGrowth') {
                chart.data.labels = data.map(item => item.date);
                chart.data.datasets[0].data = data.map(item => item.transactions || item.new_customers);
            } else if (chartType === 'topProducts') {
                chart.data.labels = data.map(item => item.name);
                chart.data.datasets[0].data = data.map(item => item.sold);
            } else if (chartType === 'paymentMethods') {
                chart.data.labels = data.map(item => item.method);
                chart.data.datasets[0].data = data.map(item => item.count);
            }
            
            chart.update('active');
            
        } catch (error) {
            console.error(`Failed to refresh ${chartType} chart:`, error);
        } finally {
            if (refreshBtn) {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = 'Refresh';
            }
        }
    }
    
    async refreshAllCharts() {
        const refreshBtn = document.getElementById('refresh-all-charts');
        if (refreshBtn) {
            refreshBtn.disabled = true;
            refreshBtn.textContent = 'Refreshing...';
        }
        
        try {
            await Promise.all(
                Object.keys(this.charts).map(chartType => this.refreshChart(chartType))
            );
            
            if (window.Toast) {
                window.Toast.success('All charts refreshed successfully');
            }
            
        } catch (error) {
            console.error('Failed to refresh all charts:', error);
            if (window.Toast) {
                window.Toast.error('Failed to refresh some charts');
            }
        } finally {
            if (refreshBtn) {
                refreshBtn.disabled = false;
                refreshBtn.textContent = 'Refresh All';
            }
        }
    }
    
    startAutoRefresh() {
        this.stopAutoRefresh(); // Clear existing interval
        
        this.refreshInterval = setInterval(() => {
            this.refreshAllCharts();
        }, this.refreshRate);
        
        // Update UI
        const indicator = document.getElementById('auto-refresh-indicator');
        if (indicator) {
            indicator.classList.remove('hidden');
        }
    }
    
    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
        
        // Update UI
        const indicator = document.getElementById('auto-refresh-indicator');
        if (indicator) {
            indicator.classList.add('hidden');
        }
    }
    
    // Public methods
    getChart(type) {
        return this.charts[type];
    }
    
    destroyChart(type) {
        if (this.charts[type]) {
            this.charts[type].destroy();
            delete this.charts[type];
        }
    }
    
    destroyAllCharts() {
        Object.keys(this.charts).forEach(type => {
            this.destroyChart(type);
        });
    }
    
    setRefreshRate(rate) {
        this.refreshRate = rate;
        if (this.refreshInterval) {
            this.startAutoRefresh(); // Restart with new rate
        }
    }
}

// Initialize when DOM is loaded and Chart.js is available
document.addEventListener('DOMContentLoaded', () => {
    // Check if we're on the dashboard page
    if (document.getElementById('revenue-chart') || document.querySelector('[data-dashboard-charts]')) {
        // Wait for Chart.js to load
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

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.DashboardCharts) {
        window.DashboardCharts.stopAutoRefresh();
        window.DashboardCharts.destroyAllCharts();
    }
});

export default DashboardCharts;