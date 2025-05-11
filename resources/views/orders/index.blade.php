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

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Layanan</th>
                                    <th>Jumlah</th>
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
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table {
        margin: 0 auto;
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
    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection

@section('scripts')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif
@endsection