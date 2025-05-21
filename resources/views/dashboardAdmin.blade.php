@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <main class="col-12 px-3 px-md-4 py-3 py-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah pesanan
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                @php
                    $stats = [
                        ['label' => 'Total Customers', 'value' => $totalCustomers, 'icon' => 'users', 'class' => 'primary'],
                        ['label' => 'Orders (This Month)', 'value' => $monthlyOrders, 'icon' => 'clipboard-list', 'class' => 'success'],
                        ['label' => 'Revenue (This Month)', 'value' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'), 'icon' => 'dollar-sign', 'class' => 'warning'],
                        ['label' => 'Pending Orders', 'value' => $pendingOrders, 'icon' => 'clock', 'class' => 'danger'],
                    ];
                @endphp

                @foreach($stats as $stat)
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="card border-left-{{ $stat['class'] }} shadow h-100 py-2 stats-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-{{ $stat['class'] }} text-uppercase mb-1">
                                            {{ $stat['label'] }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stat['value'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-{{ $stat['icon'] }} fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Weekly Revenue (Last 12 Weeks)</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="revenueFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter Pendapatan
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="revenueFilterDropdown">
                                    <li><button class="dropdown-item revenue-filter active" data-period="daily">Harian</button></li>
                                    <li><button class="dropdown-item revenue-filter" data-period="weekly">Mingguan</button></li>
                                    <li><button class="dropdown-item revenue-filter" data-period="monthly">Bulanan</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item revenue-type-filter active" data-type="total">Total</button></li>
                                    <li><button class="dropdown-item revenue-type-filter" data-type="unpaid">Belum Diterima</button></li>
                                    <li><button class="dropdown-item revenue-type-filter" data-type="profit">Laba Bersih</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyRevenueChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Order Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusPieChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Recent Orders</h6>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->service->name }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($order->status)
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-primary">Processing</span>
                                                @break
                                            @default
                                                <span class="badge bg-warning text-dark">Pending</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script>
// Namespace untuk menghindari konflik
window.Dashboard = window.Dashboard || {};

// Deklarasi revenueChart dalam namespace
if (!window.Dashboard.revenueChart) {
    window.Dashboard.revenueChart = null;
}

$(document).ready(function() {
    // Cegah inisialisasi ganda
    if (!window.Dashboard.initialized) {
        console.log('Initializing dashboard...');
        initCharts();
        setupFilterButtons();
        $('.revenue-filter.active').trigger('click');
        window.Dashboard.initialized = true;
    } else {
        console.log('Dashboard already initialized, skipping...');
    }
});

function initCharts() {
    console.log('Initializing charts...');
    const revenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');

    // Hancurkan chart sebelumnya jika ada
    if (window.Dashboard.revenueChart) {
        console.log('Destroying previous revenueChart...');
        window.Dashboard.revenueChart.destroy();
    }

    window.Dashboard.revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Revenue',
                data: @json($weeklyData),
                backgroundColor: 'rgba(28, 200, 138, 0.6)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    const statusCtx = document.getElementById('orderStatusPieChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($orderStatusData)),
            datasets: [{
                data: @json(array_values($orderStatusData)),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 10
                    }
                },
            }
        },
    });
}

function setupFilterButtons() {
    console.log('Setting up filter buttons...');
    let selectedRevenueType = 'total';

    $('.revenue-type-filter').on('click', function () {
        $('.revenue-type-filter').removeClass('active');
        $(this).addClass('active');
        selectedRevenueType = $(this).data('type');
        $('.revenue-filter.active').trigger('click');
    });

    $('.revenue-filter').on('click', function () {
        $('.revenue-filter').removeClass('active');
        $(this).addClass('active');
        const period = $(this).data('period');

        $.ajax({
            url: '{{ route("dashboard.stats") }}',
            type: 'GET',
            data: {
                type: 'revenue',
                period: period,
                revenue_type: selectedRevenueType
            },
            success: function (response) {
                console.log('Revenue data loaded:', response);
                const config = {
                    total: {
                        label: 'Total Pendapatan',
                        color: 'rgba(28, 200, 138, 0.6)',
                        borderColor: 'rgba(28, 200, 138, 1)'
                    },
                    unpaid: {
                        label: 'Belum Diterima',
                        color: 'rgba(246, 194, 62, 0.6)',
                        borderColor: 'rgba(246, 194, 62, 1)'
                    },
                    profit: {
                        label: 'Laba Bersih',
                        color: 'rgba(54, 185, 204, 0.6)',
                        borderColor: 'rgba(54, 185, 204, 1)'
                    }
                };

                // Pastikan chart ada sebelum update
                if (window.Dashboard.revenueChart) {
                    window.Dashboard.revenueChart.data.labels = response.labels;
                    window.Dashboard.revenueChart.data.datasets[0].data = response.data;
                    window.Dashboard.revenueChart.data.datasets[0].label = config[selectedRevenueType].label;
                    window.Dashboard.revenueChart.data.datasets[0].backgroundColor = config[selectedRevenueType].color;
                    window.Dashboard.revenueChart.data.datasets[0].borderColor = config[selectedRevenueType].borderColor;
                    window.Dashboard.revenueChart.update();
                    console.log('Revenue chart updated');
                } else {
                    console.error('revenueChart not initialized');
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to load revenue data:', error);
                alert('Gagal memuat data Revenue!');
            }
        });
    });
}
</script>
@endpush

<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(90deg, #4e73df, #1cc88a);
        color: white;
        font-weight: bold;
    }

    .card-body {
        background-color: #f8f9fc;
    }

    .stats-card {
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: scale(1.05);
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }

    @media (max-width: 576px) {
        h1.h2 {
            font-size: 1.5rem;
        }

        .card-header h6 {
            font-size: 0.95rem;
        }

        .dropdown-toggle, .btn, .table th, .table td {
            font-size: 0.8rem;
        }

        .table td, .table th {
            white-space: nowrap;
        }
    }
</style>