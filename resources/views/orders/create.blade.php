<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan - Sistem Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .form-container h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        .required:after {
            content: " *";
            color: #dc3545;
            font-size: 0.9rem;
        }
        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        .form-control[readonly] {
            background-color: #f8f9fa;
            font-weight: 500;
            color: #333;
        }
        .invalid-feedback {
            font-size: 0.85rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-print {
            background-color: #28a745;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-print:hover {
            background-color: #218838;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .total-price-section {
            background-color: #f1faff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e7ff;
        }
        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .receipt-section {
            background-color: #ffffff;
            padding: 20px;
        }
        .receipt-section h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .receipt-section p {
            margin: 8px 0;
            font-size: 1rem;
            display: flex;
            justify-content: space-between;
        }
        .receipt-section p strong {
            width: 200px;
            font-weight: 500;
            color: #444;
        }
        .receipt-section p span {
            flex: 1;
            text-align: left;
            color: #333;
        }
        @media print {
            body {
                background: none;
            }
            .modal, .modal-backdrop, .form-container, .btn-print, .btn-secondary {
                display: none !important;
            }
            .receipt-section {
                padding: 10px;
                border: none;
                box-shadow: none;
            }
            .modal-content {
                border: none;
                box-shadow: none;
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                margin: 20px;
            }
            .form-container h1 {
                font-size: 1.5rem;
            }
            .btn-primary, .btn-secondary, .btn-danger, .btn-print {
                width: 100%;
                margin-bottom: 10px;
            }
            .receipt-section p {
                flex-direction: column;
            }
            .receipt-section p strong {
                width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="form-container" id="form-container">
            <h1 class="text-center">Tambah Pesanan Baru</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                @csrf
                
                <div class="form-section">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label required">Pelanggan</label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">Pilih Pelanggan</option>
                                @forelse ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada pelanggan tersedia</option>
                                @endforelse
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="service_id" class="form-label required">Layanan</label>
                            <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                <option value="">Pilih Layanan</option>
                                @forelse ($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id) data-price="{{ $service->price_per_kg }}" data-name="{{ $service->name }}">
                                        {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg)
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada layanan tersedia</option>
                                @endforelse
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label required">Berat (kg)</label>
                            <input type="number" step="0.1" min="0.1" max="20" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" required>
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="total_price" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" id="total_price" readonly>
                            <input type="hidden" name="total_price" id="hidden_total_price">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pickup_date" class="form-label required">Tanggal Pengambilan</label>
                            <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" id="pickup_date" name="pickup_date" value="{{ old('pickup_date', date('Y-m-d')) }}" required>
                            @error('pickup_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <input type="checkbox" id="pickup_service" name="pickup_service" value="yes" @checked(old('pickup_service') == 'yes')>
                                <label for="pickup_service" class="form-label ms-2">Butuh Penjemputan?</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="delivery_date" class="form-label required">Tanggal Pengiriman</label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', date('Y-m-d', strtotime('+3 days'))) }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <input type="checkbox" id="delivery_service" name="delivery_service" value="yes" @checked(old('delivery_service') == 'yes')>
                                <label for="delivery_service" class="form-label ms-2">Butuh Pengantaran?</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label required">Metode Pembayaran</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="online" @selected(old('payment_method') == 'online')>Pembayaran Online</option>
                                <option value="cash" @selected(old('payment_method') == 'cash')>Bayar di Tempat</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <div class="mt-3">
                                    <label for="status" class="form-label required">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="pending" @selected(old('status', 'pending') == 'pending')>Pending</option>
                                        <option value="processing" @selected(old('status') == 'processing')>Sedang Diproses</option>
                                        <option value="completed" @selected(old('status') == 'completed')>Selesai</option>
                                        <option value="cancel" @selected(old('status') == 'cancel')>Dibatalkan</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="status" value="pending">
                            @endif
                        </div>
                        <div class="col-md-6 mb-3 d-flex flex-column justify-content-end" style="height:100%">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="5" style="min-height: 90px; height: 100%; resize: vertical; display: block;" placeholder="Tambahkan instruksi khusus di sini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary w-40 mb-2 mb-md-0">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pesanan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Struk Modal -->
    <div class="modal fade" id="strukModal" tabindex="-1" aria-labelledby="strukModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="strukModalLabel">Struk Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body receipt-section" id="strukModalBody">
                    <!-- Struk content will be injected here -->
                </div>
                <div class="modal-footer">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const weightInput = document.getElementById('weight');
            const totalPriceInput = document.getElementById('total_price');
            const hiddenTotalPrice = document.getElementById('hidden_total_price');
            const pickupDateInput = document.getElementById('pickup_date');
            const deliveryDateInput = document.getElementById('delivery_date');
            const form = document.getElementById('order-form');
            const strukModalEl = document.getElementById('strukModal');
            const strukModalBody = document.getElementById('strukModalBody');
            let strukModal = null;

            // Calculate total price
            function calculateTotal() {
                if (serviceSelect.value && weightInput.value) {
                    const price = parseFloat(serviceSelect.options[serviceSelect.selectedIndex].dataset.price);
                    const weight = parseFloat(weightInput.value);
                    if (!isNaN(price) && !isNaN(weight)) {
                        const total = price * weight;
                        totalPriceInput.value = 'Rp ' + total.toLocaleString('id-ID', { minimumFractionDigits: 0 });
                        hiddenTotalPrice.value = total;
                    } else {
                        totalPriceInput.value = 'Rp 0';
                        hiddenTotalPrice.value = 0;
                    }
                } else {
                    totalPriceInput.value = 'Rp 0';
                    hiddenTotalPrice.value = 0;
                }
            }

            serviceSelect.addEventListener('change', calculateTotal);
            weightInput.addEventListener('input', calculateTotal);
            calculateTotal();

            // Validate dates
            function validateDates() {
                const pickupDate = new Date(pickupDateInput.value);
                const deliveryDate = new Date(deliveryDateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (pickupDate < today) {
                    pickupDateInput.setCustomValidity('Tanggal pengambilan harus hari ini atau setelahnya.');
                } else {
                    pickupDateInput.setCustomValidity('');
                }

                if (deliveryDate <= pickupDate) {
                    deliveryDateInput.setCustomValidity('Tanggal pengiriman harus setelah tanggal pengambilan.');
                } else {
                    deliveryDateInput.setCustomValidity('');
                }
            }

            pickupDateInput.addEventListener('change', validateDates);
            deliveryDateInput.addEventListener('change', validateDates);

            // Format date to DD-MM-YYYY
            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            // Cleanup modals
            function cleanupModals() {
                console.log('Cleaning up modals...');
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                if (strukModal) {
                    strukModal.hide();
                }
                console.log('Modal cleanup completed');
            }

            // Show struk modal
            function showStrukModal(content) {
                console.log('Showing struk modal...');
                cleanupModals();
                strukModalBody.innerHTML = content;
                strukModal = new bootstrap.Modal(strukModalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                strukModal.show();
                console.log('Struk modal displayed');
            }

            // Form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    validateDates();
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    const formData = new FormData(form);
                    const csrfToken = document.querySelector('input[name="_token"]').value;

                    console.log('Submitting form to:', form.action);
                    console.log('Form data:', Object.fromEntries(formData));

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw { errorData, status: response.status };
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            // Use server-provided struk if available, otherwise construct client-side
                            const strukContent = data.struk || `
                                <h2>Struk Pesanan</h2>
                                <p><strong>ID Pesanan:</strong> <span>${data.order_id || 'N/A'}</span></p>
                                <p><strong>Pelanggan:</strong> <span>${data.customer_name || 'N/A'}</span></p>
                                <p><strong>Layanan:</strong> <span>${data.service_name || serviceSelect.options[serviceSelect.selectedIndex].dataset.name}</span></p>
                                <p><strong>Berat:</strong> <span>${data.weight || formData.get('weight')} kg</span></p>
                                <p><strong>Total Harga:</strong> <span>Rp ${data.total_price ? Number(data.total_price).toLocaleString('id-ID') : hiddenTotalPrice.value.toLocaleString('id-ID')}</span></p>
                                <p><strong>Tanggal Pengambilan:</strong> <span>${data.pickup_date ? formatDate(data.pickup_date) : formatDate(formData.get('pickup_date'))}</span></p>
                                <p><strong>Tanggal Pengiriman:</strong> <span>${data.delivery_date ? formatDate(data.delivery_date) : formatDate(formData.get('delivery_date'))}</span></p>
                                <p><strong>Metode Pembayaran:</strong> <span>${data.payment_method || formData.get('payment_method') === 'online' ? 'Pembayaran Online' : 'Bayar di Tempat'}</span></p>
                                <p><strong>Layanan Pengambilan:</strong> <span>${data.pickup_service || formData.get('pickup_service') === 'yes' ? 'Ya' : 'Tidak'}</span></p>
                                <p><strong>Layanan Pengantaran:</strong> <span>${data.delivery_service || formData.get('delivery_service') === 'yes' ? 'Ya' : 'Tidak'}</span></p>
                                ${data.notes || formData.get('notes') ? `<p><strong>Catatan:</strong> <span>${data.notes || formData.get('notes')}</span></p>` : ''}
                            `;
                            showStrukModal(strukContent);
                            // Reset form
                            form.reset();
                            calculateTotal();
                        } else {
                            const errorMessage = data.message || 'Gagal menyimpan pesanan.';
                            const errorDetails = data.errors ? Object.values(data.errors).flat().join('\n') : '';
                            alert(`${errorMessage}${errorDetails ? '\n' + errorDetails : ''}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error during form submission:', error);
                        const errorMessage = error.errorData?.message || 'Gagal menyimpan pesanan.';
                        const errorDetails = error.errorData?.errors ? Object.values(error.errorData.errors).flat().join('\n') : `HTTP error! status: ${error.status}`;
                        alert(`${errorMessage}\n${errorDetails}`);
                    });
                });
            } else {
                console.error('Form element not found');
            }

            // Handle modal close
            strukModalEl.addEventListener('hidden.bs.modal', function() {
                console.log('Struk modal closed');
                cleanupModals();
                strukModalBody.innerHTML = '';
            });
        });
    </script>
</body>
</html>