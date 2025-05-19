@extends('layouts.app')

@section('title', 'Edit Pesanan')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Pesanan #{{ $order->id }}</h4>
        </div>
        
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Informasi Customer -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Customer</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="customer_id" class="form-label required">Customer</label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Pilih Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        @selected(old('customer_id', $order->customer_id) == $customer->id)>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Detail Layanan -->
                    <div class="col-md-6">
                        <h5>Detail Layanan</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="service_id" class="form-label required">Layanan</label>
                            <select class="form-select @error('service_id') is-invalid @enderror" 
                                    id="service_id" name="service_id" required>
                                <option value="">Pilih Layanan</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" 
                                        data-price="{{ $service->price_per_kg }}"
                                        @selected(old('service_id', $order->service_id) == $service->id)>
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
                                    <input type="number" step="0.1" min="0.1"
                                           class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" name="weight"
                                           value="{{ old('weight', $order->weight) }}" required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_price_display" class="form-label">Total Harga</label>
                                    <input type="text" class="form-control" id="total_price_display" readonly>
                                    <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', $order->total_price) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadwal dan Status -->
                <div class="row">
                    <div class="col-md-6">
                        <h5>Jadwal</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="pickup_date" class="form-label required">Tanggal Penjemputan</label>
                            <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" 
                                   id="pickup_date" name="pickup_date" 
                                   value="{{ old('pickup_date', optional($order->pickup_date)->format('Y-m-d')) }}" required>
                            @error('pickup_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_date" class="form-label required">Tanggal Pengantaran</label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                   id="delivery_date" name="delivery_date" 
                                   value="{{ old('delivery_date', optional($order->delivery_date)->format('Y-m-d')) }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5>Status</h5>
                        <hr>
                        <div class="mb-3">
                            <label for="status" class="form-label required">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" @selected(old('status', $order->status) == 'pending')>Pending</option>
                                <option value="processing" @selected(old('status', $order->status) == 'processing')>Processing</option>
                                <option value="completed" @selected(old('status', $order->status) == 'completed')>Completed</option>
                                <option value="cancelled" @selected(old('status', $order->status) == 'cancelled')>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.getElementById('service_id');
    const weightInput = document.getElementById('weight');
    const totalDisplay = document.getElementById('total_price_display');
    const totalHidden = document.getElementById('total_price');

    function calculateTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const pricePerKg = parseFloat(selectedOption.dataset.price || 0);
        const weight = parseFloat(weightInput.value || 0);
        const total = pricePerKg * weight;

        totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
        totalHidden.value = total;
    }

    serviceSelect.addEventListener('change', calculateTotal);
    weightInput.addEventListener('input', calculateTotal);

    // Jalankan sekali saat halaman dimuat
    calculateTotal();
});
</script>
@endpush

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
