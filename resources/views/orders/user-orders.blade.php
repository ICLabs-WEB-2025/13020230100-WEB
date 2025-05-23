<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="row g-0">
        <div class="col-md-3">
            @include('layouts.app')
        </div>
        <div class="col-md-9">
            <div class="container-fluid mt-5" style="margin-left: -3rem;">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="mb-4 text-center">Pesanan Saya</h1>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

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
                                    @forelse($orders as $order)
                                        <tr class="transition-row">
                                            <td>#{{ $order->id }}</td>
                                            <td>
                                                @php
                                                    $status    = strtolower($order->status);
                                                    $badgeMap  = [
                                                        'completed'  => ['bg-success text-white', 'fa-circle-check'],
                                                        'cancelled'  => ['bg-danger text-white', 'fa-circle-xmark'],
                                                        'processing' => ['bg-warning text-dark', 'fa-spinner'],
                                                        'pending'    => ['bg-secondary text-white', 'fa-clock'],
                                                    ];
                                                    $badgeClass = $badgeMap[$status][0] ?? 'bg-dark text-white';
                                                    $iconClass  = $badgeMap[$status][1] ?? 'fa-question-circle';
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
                                            <td colspan="4" class="text-center text-muted">Tidak ada pesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

            <style>
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
        </div>
    </div>
</body>
</html>