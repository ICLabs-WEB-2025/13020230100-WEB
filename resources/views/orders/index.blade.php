@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <main class="col-md-12 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Daftar Pesanan</h1>
            </div>

            <!-- Filter Bar -->
            <div class="d-flex mb-4 align-items-start gap-2 flex-wrap justify-content-between">
                <!-- Container for Filter and Search -->
                <div class="d-flex align-items-start gap-2" style="min-width: 30vw;">
                    <!-- Tombol Filter Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter Pesanan
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li>
                                <form method="GET" action="{{ route('orders.index') }}" class="px-3 py-2">
                                    <!-- Status -->
                                    <div class="mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Semua</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>

                                    <!-- Tanggal -->
                                    <div class="mb-2">
                                        <label for="date_from" class="form-label">Dari Tanggal</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label for="date_to" class="form-label">Sampai Tanggal</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                    </div>

                                    <!-- Jenis Layanan -->
                                    <div class="mb-2">
                                        <label for="service_id" class="form-label">Jenis Layanan</label>
                                        <select name="service_id" id="service_id" class="form-select">
                                            <option value="">Semua Layanan</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Urutkan Berdasarkan ID -->
                                    <div class="mb-2">
                                        <label for="sort_id" class="form-label">Urutkan ID</label>
                                        <select name="sort_id" id="sort_id" class="form-select">
                                            <option value="">Default</option>
                                            <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>ID Terendah (A-Z)</option>
                                            <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>ID Tertinggi (Z-A)</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mt-3">Terapkan Filter</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Pencarian Customer -->
                    <form class="d-flex gap-2" id="searchForm" style="width: 100%; flex-shrink: 0;">
                        <input type="text" name="customer" id="customerSearch" class="form-control" placeholder="Cari nama customer..." value="{{ request('customer') }}" style="min-width: 60% !important;">
                        
                        @foreach (['status', 'date_from', 'date_to', 'service_id', 'sort_id'] as $field)
                            @if(request()->has($field))
                                <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
                            @endif
                        @endforeach
                    </form>
                </div>

                <!-- Tombol Tambah Pesanan -->
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1 my-2"></i> Tambah Pesanan
                    </a>
                </div>
            </div>

            <!-- Daftar Order -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Layanan</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>
                                            @if($order->service)
                                                {{ $order->service->name }}
                                            @else
                                                <span class="text-danger">N/A</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status == 'completed') bg-success
                                                @elseif($order->status == 'processing') bg-warning
                                                @elseif($order->status == 'cancelled') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@section('styles')
<style>
    .container-fluid {
        max-width: 1000px; /* Membatasi lebar container agar lebih rapi */
        margin: 2rem auto;
        padding: 0 15px;
    }

    .card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background-color: #f8f9fa;
        padding: 0.75rem;
    }

    .card-header .h2 {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table {
        background-color: #fff;
        border-radius: 0;
    }

    .thead-light th {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        text-align: center;
        padding: 0.75rem;
    }

    .table td {
        vertical-align: middle;
        padding: 0.75rem;
        color: #34495e;
    }

    .table tr {
        transition: background-color 0.2s ease;
    }

    .table tr:hover {
        background-color: #f1f3f5;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
        border-radius: 15px;
    }

    .btn-primary.btn-sm {
        background-color: #007bff;
    }

    .btn-primary.btn-sm:hover {
        background-color: #0056b3;
    }

    .btn-info.btn-sm {
        background-color: #17a2b8;
    }

    .btn-info.btn-sm:hover {
        background-color: #138496;
    }

    .btn-danger.btn-sm {
        background-color: #dc3545;
        border: none;
    }

    .btn-danger.btn-sm:hover {
        background-color: #c82333;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.85em;
        font-weight: 600;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    /* Responsivitas */
    @media (max-width: 768px) {
        .table {
            font-size: 0.85rem;
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .container-fluid {
            margin: 1rem auto;
            padding: 0 10px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Debounce function to limit the rate of search requests
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize DataTable and set up real-time search
    $(document).ready(function() {
        const table = $('#dataTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            }
        });

        // Real-time search on customer input
        const searchInput = document.getElementById('customerSearch');
        if (searchInput) {
            const debouncedSearch = debounce(function() {
                // Filter the "Customer" column (index 1, since ID is 0)
                table.column(1).search(searchInput.value).draw();
            }, 300);

            searchInput.addEventListener('input', debouncedSearch);
        }
    });
</script>
@endsection