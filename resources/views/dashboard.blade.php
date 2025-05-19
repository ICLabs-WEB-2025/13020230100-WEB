@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <main class="col-md-12 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Order
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
                    <div class="col-xl-3 col-md-6 mb-4">
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
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Weekly Revenue (Last 12 Weeks)</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="revenueFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.85rem;">
                                    Filter Pendapatan
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="revenueFilterDropdown">
                                    <li><button class="dropdown-item revenue-filter active" data-period="daily" style="font-size: 0.85rem;">Harian</button></li>
                                    <li><button class="dropdown-item revenue-filter" data-period="weekly" style="font-size: 0.85rem;">Mingguan</button></li>
                                    <li><button class="dropdown-item revenue-filter" data-period="monthly" style="font-size: 0.85rem;">Bulanan</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item revenue-type-filter active" data-type="total" style="font-size: 0.85rem;">Total</button></li>
                                    <li><button class="dropdown-item revenue-type-filter" data-type="unpaid" style="font-size: 0.85rem;">Belum Diterima</button></li>
                                    <li><button class="dropdown-item revenue-type-filter" data-type="profit" style="font-size: 0.85rem;">Laba Bersih</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyRevenueChart" style="max-height: 200px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Order Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusPieChart" style="max-height: 200px;"></canvas>
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
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i> Delete
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
    let orderChart, revenueChart;

    $(document).ready(function() {
        initCharts();
        setupFilterButtons();

        // 🔁 Jalankan filter aktif di awal agar sinkron
        $('.revenue-filter.active').trigger('click');
    });


    function initCharts() {
        const revenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
        revenueChart = new Chart(revenueCtx, {
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
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function setupFilterButtons() {
    let selectedRevenueType = 'total';

    // Filter untuk Revenue Type
    $('.revenue-type-filter').on('click', function () {
        $('.revenue-type-filter').removeClass('active');
        $(this).addClass('active');
        selectedRevenueType = $(this).data('type');
        $('.revenue-filter.active').trigger('click'); // Refresh chart dengan tipe baru
    });

    // Filter untuk Revenue Chart
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
                // Debug: cek data yang diterima
                console.log('Revenue Data:', response);
                
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

                // Update chart dengan konfigurasi yang sesuai
                revenueChart.data.labels = response.labels;
                revenueChart.data.datasets[0].data = response.data;
                revenueChart.data.datasets[0].label = config[selectedRevenueType].label;
                revenueChart.data.datasets[0].backgroundColor = config[selectedRevenueType].color;
                revenueChart.data.datasets[0].borderColor = config[selectedRevenueType].borderColor;

                // Format angka ke format rupiah dan pastikan nilai tidak undefined
                revenueChart.options.scales.y = {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value || 0).toLocaleString('id-ID');
                        }
                    }
                };

                revenueChart.update();
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Gagal memuat data Revenue!');
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('orderStatusPieChart').getContext('2d');
        new Chart(ctx, {
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
                            boxWidth: 10, // Reduce the size of the legend box
                            boxHeight: 10 // Optional: Adjust height if needed
                        }
                    },
                },
            },
        });
    });
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
        text-transform: uppercase;
    }

    .card-body {
        background-color: #f8f9fc;
    }

    .chart-container {
        position: relative;
        margin: auto;
        height: 300px;
        width: 100%;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add animations to cards
        const cards = document.querySelectorAll('.stats-card');
        cards.forEach(card => {
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
    });
</script>
