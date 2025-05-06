@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Pesanan</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">Tambah Pesanan</a>
</div>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
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
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection