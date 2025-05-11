@extends('layouts.app')

@section('title', null)

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
                                        @selected(old('service_id', $order->service_id) == $service->id)
                                        data-price="{{ $service->price_per_kg }}">
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
                                    <label for="total_price" class="form-label">Total Harga</label>
                                    <input type="text" class="form-control" id="total_price" 
                                           value="Rp {{ number_format(old('total_price', $order->total_price), 0, ',', '.') }}" readonly>
                                    <input type="hidden" name="total_price" id="hidden_total_price" 
                                           value="{{ old('total_price', $order->total_price) }}">
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
                                   value="{{ old('pickup_date', $order->pickup_date ? $order->pickup_date->format('Y-m-d') : '') }}" required>
                            @error('pickup_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_date" class="form-label required">Tanggal Pengantaran</label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                   id="delivery_date" name="delivery_date" 
                                   value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Status Pesanan</h5>
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
                
                <!-- Tombol Aksi -->
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const weightInput = document.getElementById('weight');
    const totalPriceInput = document.getElementById('total_price');
    const hiddenTotalPrice = document.getElementById('hidden_total_price');
    
    function calculateTotal() {
        if (serviceSelect.value && weightInput.value) {
            const price = parseFloat(serviceSelect.options[serviceSelect.selectedIndex].dataset.price);
            const weight = parseFloat(weightInput.value);
            const total = price * weight;
            
            // Format tampilan
            totalPriceInput.value = 'Rp ' + total.toLocaleString('id-ID');
            // Simpan nilai asli di input hidden
            hiddenTotalPrice.value = total;
        }
    }
    
    serviceSelect.addEventListener('change', calculateTotal);
    weightInput.addEventListener('input', calculateTotal);
    
    // Hitung saat pertama kali load
    calculateTotal();
});
</script>
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
@endsection