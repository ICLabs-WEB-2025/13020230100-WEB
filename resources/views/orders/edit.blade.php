@extends('layouts.app')

@section('title', 'Edit Pesanan')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Pesanan #{{ $order->id }}</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Customer</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="customer_id" class="form-label required">Customer</label>
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('customer_id', $order->customer_id) == $customer->id)>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <select class="form-select" id="customer_id" name="customer_id" disabled>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected($order->customer_id == $customer->id)>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5>Detail Layanan</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="service_id" class="form-label required">Layanan</label>
                            <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                <option value="">Pilih Layanan</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id', $order->service_id) == $service->id) data-price="{{ $service->price_per_kg }}">
                                        {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label required">Berat (kg)</label>
                                    <input type="number" step="0.1" min="0.1" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $order->weight) }}" required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_price" class="form-label">Total Harga</label>
                                    <input type="text" class="form-control" id="total_price" value="Rp {{ number_format(old('total_price', $order->total_price), 0, ',', '.') }}" readonly>
                                    <input type="hidden" name="total_price" id="hidden_total_price" value="{{ old('total_price', $order->total_price) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Jadwal</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="pickup_date" class="form-label required">Tanggal Penjemputan</label>
                            <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" id="pickup_date" name="pickup_date" value="{{ old('pickup_date', optional($order->pickup_date)->format('Y-m-d')) }}" required>
                            @error('pickup_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label required">Tanggal Pengantaran</label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', optional($order->delivery_date)->format('Y-m-d')) }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5>Status Pesanan</h5>
                        <hr>
                        @php $statusValue = old('status', $order->status ?? 'pending'); @endphp
                        <div class="mb-3">
                            <label for="status" class="form-label required">Status</label>
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" @selected($statusValue === 'pending')>Menunggu</option>
                                    <option value="processing" @selected($statusValue === 'processing')>Sedang Diproses</option>
                                    <option value="completed" @selected($statusValue === 'completed')>Selesai</option>
                                    <option value="cancelled" @selected($statusValue === 'cancelled')>Dibatalkan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <input type="hidden" name="status" value="{{ old('status', $order->status ?? 'pending') }}">
                                <span class="form-control-plaintext">{{ ucfirst(old('status', $order->status)) }}</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label required">Metode Pembayaran</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">Pilih</option>
                                <option value="online" @selected(old('payment_method', $order->payment_method)==='online')>Online</option>
                                <option value="cash" @selected(old('payment_method', $order->payment_method)==='cash')>Tunai</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                @if(auth()->user() && auth()->user()->role === 'admin')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pickup_service" class="form-label required">Layanan Pengambilan</label>
                            <select class="form-select @error('pickup_service') is-invalid @enderror" id="pickup_service" name="pickup_service" required>
                                <option value="yes" @selected(old('pickup_service', $order->pickup_service)=='yes')>Ya</option>
                                <option value="no" @selected(old('pickup_service', $order->pickup_service)=='no')>Tidak</option>
                            </select>
                            @error('pickup_service')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="delivery_service" class="form-label required">Layanan Pengantaran</label>
                            <select class="form-select @error('delivery_service') is-invalid @enderror" id="delivery_service" name="delivery_service" required>
                                <option value="yes" @selected(old('delivery_service', $order->delivery_service)=='yes')>Ya</option>
                                <option value="no" @selected(old('delivery_service', $order->delivery_service)=='no')>Tidak</option>
                            </select>
                            @error('delivery_service')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @else
                    <input type="hidden" name="pickup_service" value="{{ old('pickup_service', $order->pickup_service ?? 'yes') }}">
                    <input type="hidden" name="delivery_service" value="{{ old('delivery_service', $order->delivery_service ?? 'yes') }}">
                @endif

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .required:after {
        content: " *";
        color: red;
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const weightInput = document.getElementById('weight');
    const totalDisplay = document.getElementById('total_price');
    const hiddenTotal = document.getElementById('hidden_total_price');

    function calculateTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const pricePerKg = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const weight = parseFloat(weightInput.value) || 0;
        const total = pricePerKg * weight;
        totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
        hiddenTotal.value = total;
    }

    calculateTotal();
    serviceSelect.addEventListener('change', calculateTotal);
    weightInput.addEventListener('input', calculateTotal);
});
</script>
@endpush
