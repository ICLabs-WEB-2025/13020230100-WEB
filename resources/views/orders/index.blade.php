@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <main class="col-md-12 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Daftar Pesanan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Pesanan
                    </a>
                </div>
            </div>


            <!-- Filter Bar -->
            <div class="d-flex mb-4 align-items-start gap-2 flex-wrap">
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
                <form method="GET" action="{{ route('orders.index') }}" class="d-flex gap-2">
                    <input type="text" name="customer" class="form-control" placeholder="Cari nama customer..." value="{{ request('customer') }}">
                    
                    @foreach (['status', 'date_from', 'date_to', 'service_id', 'sort_id'] as $field)
                        @if(request()->has($field))
                            <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
                        @endif
                    @endforeach

                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
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
    .form-label {
        font-weight: bold;
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
    .btn {
        padding: 0.25rem 0.5rem;
        margin: 0 2px;
    }
</style>
@endsection
