<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Laundry System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Order List</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Add Order</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="mb-3">
            <select id="statusFilter" class="form-select" onchange="filterOrders()">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <table class="table table-striped" id="orderTable">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Weight (kg)</th>
                    <th>Status</th>
                    <th>Pickup Date</th>
                    <th>Delivery Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr data-status="{{ $order->status }}">
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->service->name }}</td>
                        <td>{{ $order->weight }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->pickup_date }}</td>
                        <td>{{ $order->delivery_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterOrders() {
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#orderTable tbody tr');
            rows.forEach(row => {
                if (status === '' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>