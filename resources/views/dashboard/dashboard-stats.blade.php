@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-6">
        @php
            $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
        @endphp

        @if ($unreadCount > 0)
            <div class="col-lg-12">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex align-items-baseline">
                        <span class="alert-icon rounded">
                            <i class="icon-base ti ti-bell ti-md"></i>
                        </span>
                        <span>
                            You have {{ $unreadCount }} new notifications to read!
                        </span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Auto-refresh control -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-refresh me-2"></i>
                            <span>Auto-refresh: </span>
                            <div class="form-check form-check-inline ms-2">
                                <input class="form-check-input" type="radio" name="refreshInterval" id="off" value="0" checked>
                                <label class="form-check-label" for="off">Off</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="refreshInterval" id="30s" value="30000">
                                <label class="form-check-label" for="30s">30s</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="refreshInterval" id="1m" value="60000">
                                <label class="form-check-label" for="1m">1m</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="refreshInterval" id="5m" value="300000">
                                <label class="form-check-label" for="5m">5m</label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="refreshAllData()">
                            <i class="ti ti-refresh me-1"></i> Refresh Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SUMMARY CARDS ================= -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Transfers</h6>
                            <h3 id="totalTransfers">{{ $summaryStats['total_transfers'] ?? 0 }}</h3>
                        </div>
                        <i class="ti ti-arrows-exchange fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Alterations</h6>
                            <h3 id="totalAlterations">{{ $summaryStats['total_alterations'] ?? 0 }}</h3>
                        </div>
                        <i class="ti ti-edit fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Permits</h6>
                            <h3 id="totalPermits">{{ $summaryStats['total_permits'] ?? 0 }}</h3>
                        </div>
                        <i class="ti ti-license fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Active Vehicles</h6>
                            <h3 id="activeVehicles">{{ $summaryStats['active_vehicles'] ?? 0 }}</h3>
                        </div>
                        <i class="ti ti-car fs-1 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= BILLING SUMMARY CARDS ================= -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Revenue</h6>
                            <h3 id="totalRevenue">Rs. {{ number_format($billingSummary['total_revenue'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="ti ti-currency-rupee fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Pending Amount</h6>
                            <h3 id="pendingAmount">Rs. {{ number_format($billingSummary['pending_payments'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="ti ti-clock fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Monthly Revenue</h6>
                            <h3 id="monthlyRevenue">Rs. {{ number_format($billingSummary['monthly_revenue'] ?? 0, 2) }}</h3>
                        </div>
                        <i class="ti ti-chart-bar fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= CHARTS ================= -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h5>Transfers Overview</h5>
                    <div>
                        <button class="btn btn-sm btn-primary" onclick="exportChartAsPDF()">Export PDF</button>
                        <button class="btn btn-sm btn-success" onclick="exportChartAsExcel()">Export Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="transferChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Permits Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="permitChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ================= WORK TYPE DISTRIBUTION ================= -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Work Type Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="workTypeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ================= MONTHLY REVENUE CHART ================= -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Monthly Revenue Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ================= RECENT TRANSFERS TABLE ================= -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h5>Recent Transfers</h5>
                    <button class="btn btn-sm btn-info" onclick="refreshRecentTransfers()">
                        <i class="ti ti-refresh"></i> Refresh
                    </button>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="recentTransfersTable">
                        <thead>
                            <tr>
                                <th>Case No</th>
                                <th>Vehicle Reg</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentTransfersBody">
                            @isset($recentTransfers)
                                @foreach($recentTransfers as $transfer)
                                <tr>
                                    <td>{{ $transfer['case_no'] }}</td>
                                    <td>{{ $transfer['vehicle_reg_no'] }}</td>
                                    <td>{{ $transfer['from_name'] }}</td>
                                    <td>{{ $transfer['to_name'] }}</td>
                                    <td>{{ $transfer['date'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer['status'] == 'open' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transfer['status']) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= RECENT CASES ================= -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Recent Cases Activity</h5>
                </div>
                <div class="card-body">
                    <div class="timeline" id="recentCasesTimeline">
                        @isset($recentCases)
                            @foreach($recentCases as $case)
                            <div class="timeline-item">
                                <div class="timeline-point bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>{{ $case->case_no }} - {{ $case->vehicle_reg_no }}</h6>
                                    <p class="mb-0">
                                        Work Type: {{ $case->work_type }} |
                                        Status: <span class="badge bg-{{ $case->status == 'open' ? 'success' : 'danger' }}">
                                            {{ ucfirst($case->status) }}
                                        </span>
                                    </p>
                                    <small class="text-muted">{{ $case->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart instances
        let transferChart, permitChart, workTypeChart, revenueChart;
        let refreshInterval = null;

        // Initialize charts on page load
        document.addEventListener("DOMContentLoaded", function() {
            initializeCharts();
            setupAutoRefresh();
        });

        function initializeCharts() {
            // Transfer Line Chart
            const ctx1 = document.getElementById('transferChart');
            transferChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($transferChart['labels'] ?? []),
                    datasets: [{
                        label: 'Transfers',
                        data: @json($transferChart['data'] ?? []),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    }
                }
            });

            // Permit Pie Chart
            const ctx2 = document.getElementById('permitChart');
            permitChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: @json($permitStatus['labels'] ?? []),
                    datasets: [{
                        data: @json($permitStatus['data'] ?? []),
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(239, 68, 68)',
                            'rgb(107, 114, 128)'
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            // Work Type Chart
            const ctx3 = document.getElementById('workTypeChart');
            workTypeChart = new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: @json($workTypeDistribution['labels'] ?? []),
                    datasets: [{
                        label: 'Number of Cases',
                        data: @json($workTypeDistribution['data'] ?? []),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Revenue Chart
            const ctx4 = document.getElementById('revenueChart');
            revenueChart = new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: @json($monthlyRevenue['labels'] ?? []),
                    datasets: [{
                        label: 'Revenue (Rs.)',
                        data: @json($monthlyRevenue['data'] ?? []),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'Rs. ' + context.parsed.y.toLocaleString();
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Setup auto-refresh
        function setupAutoRefresh() {
            document.querySelectorAll('input[name="refreshInterval"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (refreshInterval) {
                        clearInterval(refreshInterval);
                        refreshInterval = null;
                    }

                    const interval = parseInt(this.value);
                    if (interval > 0) {
                        refreshInterval = setInterval(refreshAllData, interval);
                    }
                });
            });
        }

        // Refresh all data
        async function refreshAllData() {
            await Promise.all([
                refreshSummaryStats(),
                refreshTransferChart(),
                refreshPermitChart(),
                refreshRecentTransfers(),
                refreshWorkTypeChart(),
                refreshRevenueChart()
            ]);
        }

        // Refresh summary statistics
        async function refreshSummaryStats() {
            try {
                const response = await fetch('{{ route("dashboard.refresh") }}?type=summary');
                const data = await response.json();

                // Update basic stats
                document.getElementById('totalTransfers').textContent = data.total_transfers || 0;
                document.getElementById('totalAlterations').textContent = data.total_alterations || 0;
                document.getElementById('totalPermits').textContent = data.total_permits || 0;
                document.getElementById('activeVehicles').textContent = data.active_vehicles || 0;

                // UPDATE BILLING DATA - THIS IS WHAT YOU'RE MISSING!
                document.getElementById('totalRevenue').textContent = 'Rs. ' + (data.total_revenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('pendingAmount').textContent = 'Rs. ' + (data.pending_payments || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('monthlyRevenue').textContent = 'Rs. ' + (data.monthly_revenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            } catch (error) {
                console.error('Error refreshing summary stats:', error);
            }
        }

        // Refresh transfer chart
        async function refreshTransferChart() {
            try {
                const response = await fetch('{{ route("dashboard.refresh") }}?type=transfers');
                const data = await response.json();

                transferChart.data.labels = data.labels || [];
                transferChart.data.datasets[0].data = data.data || [];
                transferChart.update();
            } catch (error) {
                console.error('Error refreshing transfer chart:', error);
            }
        }

        // Refresh permit chart
        async function refreshPermitChart() {
            try {
                const response = await fetch('{{ route("dashboard.refresh") }}?type=permits');
                const data = await response.json();

                permitChart.data.labels = data.labels || [];
                permitChart.data.datasets[0].data = data.data || [];
                permitChart.update();
            } catch (error) {
                console.error('Error refreshing permit chart:', error);
            }
        }

        // Refresh work type chart
        async function refreshWorkTypeChart() {
            try {
                const response = await fetch('{{ route("dashboard.refresh") }}?type=work_types');
                const data = await response.json();

                workTypeChart.data.labels = data.labels || [];
                workTypeChart.data.datasets[0].data = data.data || [];
                workTypeChart.update();
            } catch (error) {
                console.error('Error refreshing work type chart:', error);
            }
        }

        // Refresh revenue chart
        async function refreshRevenueChart() {
            try {
                const response = await fetch('{{ route("dashboard.refresh") }}?type=revenue');
                const data = await response.json();

                revenueChart.data.labels = data.labels || [];
                revenueChart.data.datasets[0].data = data.data || [];
                revenueChart.update();
            } catch (error) {
                console.error('Error refreshing revenue chart:', error);
            }
        }

        // Refresh recent transfers table
        async function refreshRecentTransfers() {
            try {
                const response = await fetch('{{ route("dashboard.refresh") }}?type=recent');
                const transfers = await response.json();

                const tbody = document.getElementById('recentTransfersBody');
                tbody.innerHTML = '';

                if (transfers && transfers.length > 0) {
                    transfers.forEach(transfer => {
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td>${transfer.case_no || 'N/A'}</td>
                            <td>${transfer.vehicle_reg_no || 'N/A'}</td>
                            <td>${transfer.from_name || 'N/A'}</td>
                            <td>${transfer.to_name || 'N/A'}</td>
                            <td>${transfer.date || 'N/A'}</td>
                            <td>
                                <span class="badge bg-${transfer.status === 'open' ? 'success' : 'danger'}">
                                    ${transfer.status ? transfer.status.charAt(0).toUpperCase() + transfer.status.slice(1) : 'N/A'}
                                </span>
                            </td>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No recent transfers found</td></tr>';
                }
            } catch (error) {
                console.error('Error refreshing recent transfers:', error);
            }
        }

        // Export functions
        function exportChartAsPDF() {
            alert('PDF export functionality would be implemented here');
        }

        function exportChartAsExcel() {
            alert('Excel export functionality would be implemented here');
        }
    </script>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-point {
            position: absolute;
            left: -30px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            top: 5px;
        }

        .timeline-point.bg-primary {
            background-color: #3b82f6;
        }

        .timeline-content {
            padding-left: 15px;
            border-left: 2px solid #e5e7eb;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
@endsection
