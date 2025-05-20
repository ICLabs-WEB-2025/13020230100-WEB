@extends('layouts.app')
@section('title', 'Detail Pesanan')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    html, body {
        height: 100%;
        margin: 0;
        background-color: white;
    }

    .container {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .full-height {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        overflow: hidden;
    }

    .card {
        border-radius: 10px;
        width: 100%;
        max-width: 900px;
        max-height: 95vh;
        overflow-y: auto;
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }

    .card-footer {
        border-radius: 0 0 10px 10px !important;
    }

    p {
        margin-bottom: 0.8rem;
    }

    /* Tambahan agar scrollbar dalam card lebih bagus */
    .card::-webkit-scrollbar {
        width: 6px;
    }

    .card::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
</style>

<div class="full-height">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail Pesanan #{{ $order->id }}</h4>
                <span class="badge bg-light text-dark">
                    {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y H:i') }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informasi Customer</h5>
                    <hr>
                    <p><strong>Nama:</strong> {{ $order->customer->name }}</p>
                    <p><strong>Telepon:</strong> {{ $order->customer->phone }}</p>
                    <p><strong>Alamat:</strong> {{ $order->customer->address ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <h5>Detail Layanan</h5>
                    <hr>
                    <p><strong>Jenis Layanan:</strong> {{ $order->service->name }}</p>
                    <p><strong>Berat:</strong> {{ $order->weight }} kg</p>
                    <p><strong>Harga per kg:</strong> Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}</p>
                    <p><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Jadwal</h5>
                    <hr>
                    <p><strong>Tanggal Penjemputan:</strong> {{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d F Y') }}</p>
                    <p><strong>Tanggal Pengantaran:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d F Y') }}</p>
                </div>

                <div class="col-md-6">
                    <h5>Status Pesanan</h5>
                    <hr>
                    <p>
                        <strong>Status:</strong>
                        <span class="badge 
                            @if($order->status == 'completed') bg-success
                            @elseif($order->status == 'processing') bg-warning
                            @elseif($order->status == 'cancelled') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Catatan:</strong> {{ $order->notes ?? '-' }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-center">Barcode Pesanan</h5>
                    <hr>
                    <form action="{{ route('orders.updateStatus', ['id' => $order->id]) }}" method="POST">
                        @csrf
                        <div class="p-3 border rounded bg-light text-center">
                            {!! QrCode::size(150)->generate(route('orders.updateStatus', ['id' => $order->id])) !!}
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>

                <div class="btn-group">
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Pesanan
                    </a>

                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
