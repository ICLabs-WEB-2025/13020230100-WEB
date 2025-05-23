@extends('layouts.app')

@section('title', '👋 Welcome back, ' . auth()->user()->name . '!')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-white bg-primary h-100 hover-zoom">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-box me-2"></i>Total Orders</h5>
                <h3>{{ $totalOrders }}</h3>
                <p class="card-text">Manage your recent orders here.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-white bg-success h-100 hover-zoom">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-dollar-sign me-2"></i>Total Spent</h5>
                <h3>Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                <p class="card-text">Track your spending on our services.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-white bg-warning h-100 hover-zoom">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clock me-2"></i>Pending Orders</h5>
                <h3>{{ $pendingOrders }}</h3>
                <p class="card-text">Check the status of your pending orders.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-white bg-info h-100 hover-zoom">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-plus-circle me-2"></i>New Order</h5>
                <p class="card-text">Place a new order online.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-light">Order Now</a>
            </div>
        </div>
    </div>
</div>

<h2 class="mt-5 mb-3">📦 Recent Orders</h2>
<div class="table-responsive shadow-sm rounded">
    <table class="table table-hover table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Order ID</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr class="transition-row">
                <td>#{{ $order->id }}</td>
                <td>
                    @php
                        $status = strtolower($order->status);
                        $badgeMap = [
                            'completed' => ['bg-success text-white', 'fa-circle-check'],
                            'cancelled' => ['bg-danger text-white', 'fa-circle-xmark'],
                            'processing' => ['bg-warning text-dark', 'fa-spinner'],
                            'pending' => ['bg-secondary text-white', 'fa-clock'],
                        ];
                        $badgeClass = $badgeMap[$status][0] ?? 'bg-dark text-white';
                        $iconClass = $badgeMap[$status][1] ?? 'fa-question-circle';
                    @endphp

                    <span class="badge rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-2 {{ $badgeClass }}">
                        <i class="fas {{ $iconClass }}"></i> {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>${{ number_format($order->total_price, 2) }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No recent orders available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    .hover-zoom:hover {
        transform: scale(1.03);
        transition: transform 0.3s ease;
    }

    .transition-row {
        transition: background-color 0.2s ease-in-out;
    }

    .transition-row:hover {
        background-color: #f1f1f1;
    }

    .badge i {
        font-size: 0.9rem;
    }
</style>
@endsection