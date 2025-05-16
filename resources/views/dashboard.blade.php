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
                        <div class="card border-left-{{ $stat['class'] }} shadow h-100 py-2">
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

            <!-- Filter untuk Order -->
            <div class="row mb-4">
                <div class="col-xl-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Filter Order</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary order-filter active" data-period="daily">Harian</button>
                                <button type="button" class="btn btn-outline-primary order-filter" data-period="weekly">Mingguan</button>
                                <button type="button" class="btn btn-outline-primary order-filter" data-period="monthly">Bulanan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter untuk Revenue -->
                <div class="col-xl-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Filter Revenue</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-success revenue-filter active" data-period="daily">Harian</button>
                                <button type="button" class="btn btn-outline-success revenue-filter" data-period="weekly">Mingguan</button>
                                <button type="button" class="btn btn-outline-success revenue-filter" data-period="monthly">Bulanan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Charts Row -->
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Daily Orders (Last 30 Days)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="dailyOrderChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Weekly Revenue (Last 12 Weeks)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyRevenueChart"></canvas>
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
    let orderChart, revenueChart;

    $(document).ready(function() {
        initCharts();
        setupFilterButtons();
    });

    function initCharts() {
        const orderCtx = document.getElementById('dailyOrderChart').getContext('2d');
        orderChart = new Chart(orderCtx, {
            type: 'line',
            data: {
                labels: @json($dailyLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($dailyData),
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true },
                    x: { ticks: { autoSkip: true, maxTicksLimit: 10 } }
                }
            }
        });

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
    // Filter untuk Order Chart
    $('.order-filter').on('click', function () {
        $('.order-filter').removeClass('active');
        $(this).addClass('active');
        const period = $(this).data('period');

        $.ajax({
            url: '{{ route("dashboard.stats") }}',
            type: 'GET',
            data: { type: 'order', period: period },
            success: function (response) {
                orderChart.data.labels = response.labels;
                orderChart.data.datasets[0].data = response.data;
                orderChart.update();
            },
            error: function () {
                alert('Gagal memuat data Order!');
            }
        });
    });

    // Filter untuk Revenue Chart
    $('.revenue-filter').on('click', function () {
        $('.revenue-filter').removeClass('active');
        $(this).addClass('active');
        const period = $(this).data('period');

        $.ajax({
            url: '{{ route("dashboard.stats") }}',
            type: 'GET',
            data: { type: 'revenue', period: period },
            success: function (response) {
                revenueChart.data.labels = response.labels;
                revenueChart.data.datasets[0].data = response.data;
                revenueChart.update();
            },
            error: function () {
                alert('Gagal memuat data Revenue!');
            }
        });
    });
}


    

</script>
@endpush
