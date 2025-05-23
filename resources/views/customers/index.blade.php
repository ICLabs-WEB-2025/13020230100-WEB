@extends('layouts.app')

@section('title', 'Daftar Customer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Customer</h6>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Customer
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="customerTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Jumlah Pesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>
                                        <span class="badge {{ $customer->orders->count() > 0 ? 'bg-warning' : 'bg-success' }}">
                                            {{ $customer->orders->count() }} pesanan
                                        </span>
                                        @if($customer->orders->count() > 0)
                                            <a href="{{ route('orders.index', ['customer' => $customer->name]) }}" class="btn btn-link btn-sm p-0 ms-1" title="Lihat pesanan customer ini">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($customer->orders->count() > 0)
                                            <button type="button" class="btn btn-danger btn-sm" disabled title="Customer memiliki {{ $customer->orders->count() }} pesanan dan tidak dapat dihapus">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        @else
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus customer {{ $customer->name }}?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#customerTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            }
        });
    });
</script>
@endsection