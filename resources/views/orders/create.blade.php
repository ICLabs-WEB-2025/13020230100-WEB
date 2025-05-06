<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order - Laundry System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .required:after {
            content: " *";
            color: red;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="form-container">
            <h1 class="mb-4 text-center">Add New Order</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="customer_id" class="form-label required">Customer</label>
                        <select class="form-select @error('customer_id') is-invalid @enderror" 
                                id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                    @selected(old('customer_id') == $customer->id)>
                                    {{ $customer->name }} - {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
    <label for="service_id" class="form-label required">Service</label>
    <select class="form-select @error('service_id') is-invalid @enderror" 
            id="service_id" name="service_id" required>
        <option value="">Select Service</option>
        @forelse ($services as $service)
            <option value="{{ $service->id }}" 
                @selected(old('service_id') == $service->id)
                data-price="{{ $service->price_per_kg }}">
                {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)
            </option>
        @empty
            <option value="" disabled>No services available</option>
        @endforelse
    </select>
    @error('service_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="weight" class="form-label required">Weight (kg)</label>
                        <input type="number" step="0.1" min="0.1" max="20" 
                               class="form-control @error('weight') is-invalid @enderror" 
                               id="weight" name="weight" value="{{ old('weight') }}" 
                               required>
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="total_price" class="form-label">Total Price</label>
                        <input type="text" class="form-control" id="total_price" 
                               readonly value="Rp 0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                            <option value="processing" @selected(old('status') == 'processing')>Processing</option>
                            <option value="completed" @selected(old('status') == 'completed')>Completed</option>
                            <option value="cancelled" @selected(old('status') == 'cancelled')>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pickup_date" class="form-label required">Pickup Date</label>
                        <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" 
                               id="pickup_date" name="pickup_date" 
                               value="{{ old('pickup_date', date('Y-m-d')) }}" required>
                        @error('pickup_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="delivery_date" class="form-label required">Delivery Date</label>
                        <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                               id="delivery_date" name="delivery_date" 
                               value="{{ old('delivery_date', date('Y-m-d', strtotime('+3 days'))) }}" required>
                        @error('delivery_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Orders
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const weightInput = document.getElementById('weight');
    const totalPriceInput = document.getElementById('total_price');
    const hiddenTotalPrice = document.createElement('input');
    
    // Buat input hidden untuk menyimpan nilai numerik
    hiddenTotalPrice.type = 'hidden';
    hiddenTotalPrice.name = 'total_price';
    hiddenTotalPrice.id = 'hidden_total_price';
    document.querySelector('form').appendChild(hiddenTotalPrice);
    
    function calculateTotal() {
        if (serviceSelect.value && weightInput.value) {
            const price = parseFloat(serviceSelect.options[serviceSelect.selectedIndex].dataset.price);
            const weight = parseFloat(weightInput.value);
            const total = price * weight;
            
            // Format tampilan
            totalPriceInput.value = 'Rp ' + total.toLocaleString('id-ID');
            // Simpan nilai asli di input hidden
            hiddenTotalPrice.value = total;
        } else {
            totalPriceInput.value = 'Rp 0';
            hiddenTotalPrice.value = 0;
        }
    }
    
    serviceSelect.addEventListener('change', calculateTotal);
    weightInput.addEventListener('input', calculateTotal);
    
    // Initialize calculation
    calculateTotal();
});
</script>
</body>
</html>     