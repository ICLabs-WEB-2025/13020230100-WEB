```blade
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <main class="col-12 px-3 px-md-4 py-3 py-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dasbor</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Pesanan
                    </a>
                </div>
            </div>

            @if(count($ordersToConfirm->filter(function($order) {
                return $order->pickup_service === 'yes' && $order->status === 'pending';
            })))
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert" style="font-size: 1rem; padding: 0.5rem 1rem;">
                <i class="fas fa-bell fa-lg me-2"></i>
                <div>
                    <b>{{ $ordersToConfirm->filter(function($order) {
                        return $order->pickup_service === 'yes' && $order->status === 'pending';
                    })->count() }}</b> pesanan membutuhkan penjemputan dan menunggu aksi admin.
                    <a href="#cardButuhJemput" class="btn btn-sm btn-outline-primary py-0 px-2 ms-2">Lihat Daftar</a>
                </div>
            </div>
            @endif

            <!-- Kartu Statistik -->
            <div class="row mb-4">
                @php
                    $stats = [
                        ['label' => 'Total Pelanggan', 'value' => $totalCustomers, 'icon' => 'users', 'class' => 'primary'],
                        ['label' => 'Pesanan (Bulan Ini)', 'value' => $monthlyOrders, 'icon' => 'clipboard-list', 'class' => 'success'],
                        ['label' => 'Pendapatan (Bulan Ini)', 'value' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'), 'icon' => 'money-bill-wave', 'class' => 'warning'],
                        ['label' => 'Pesanan Pending', 'value' => $pendingOrders, 'icon' => 'clock', 'class' => 'danger'],
                    ];
                @endphp

                @foreach($stats as $stat)
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="card border-left-{{ $stat['class'] }} shadow h-100 py-2 stats-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-{{ $stat['class'] }} text-uppercase mb-1">
                                            {{ $stat['label'] }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stat['value'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-{{ $stat['icon'] }} fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Baris Grafik -->
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Pendapatan Mingguan (12 Minggu Terakhir)</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="revenueFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter Pendapatan
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="revenueFilterDropdown">
                                    <li><button class="dropdown-item revenue-filter active" data-period="daily">Harian</button></li>
                                    <li><button class="dropdown-item revenue-filter" data-period="weekly">Mingguan</button></li>
                                    <li><button class="dropdown-item revenue-filter" data-period="monthly">Bulanan</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item revenue-type-filter active" data-type="total">Total</button></li>
                                    <li><button class="dropdown-item revenue-type-filter" data-type="unpaid">Belum Diterima</button></li>
                                    <li><button class="dropdown-item revenue-type-filter" data-type="profit">Laba Bersih</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyRevenueChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Distribusi Status Pesanan</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusPieChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Pesanan Terbaru -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Pesanan Terbaru</h6>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-list me-1"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders->whereIn('status', ['pending', 'processing', 'completed', 'cancel']) as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->service->name }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($order->status)
                                            @case('completed')
                                                <span class="badge bg-success">Selesai</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-primary">Diproses</span>
                                                @break
                                            @case('cancel')
                                                <span class="badge bg-danger">Dibatalkan</span>
                                                @break
                                            @default
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
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

            <!-- Kartu Baru Status Pesanan -->
            @php
                $selesaiAntar = \App\Models\Order::with(['customer'])
                    ->where('delivery_service', 'yes')
                    ->where('payment_method', 'online')
                    ->where('status', 'completed')
                    ->latest()
                    ->get();
            @endphp
            <div class="row mb-4" id="cardButuhJemput">
                <div class="col-12">
                    <div class="card border-info mb-3">
                        <div class="card-header bg-info text-dark fw-bold">Pesanan Butuh Penjemputan</div>
                        <div class="card-body">
                            @php
                                $butuhJemput = $ordersToConfirm->filter(function($order) {
                                    return $order->pickup_service === 'yes' && $order->status === 'pending';
                                });
                            @endphp
                            @if($butuhJemput->count())
                                <ul class="list-group list-group-flush">
                                    @foreach($butuhJemput as $order)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            #{{ $order->id }} - {{ $order->customer->name }}    
                                            <span class="badge bg-info text-dark ms-2">Butuh Penjemputan</span>
                                        </span>
                                        <span>
                                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline butuh-jemput-form">
                                                @csrf
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="btn btn-sm btn-success ms-1"><i class="fas fa-check"></i> Konfirmasi</button>
                                            </form>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted">Tidak ada pesanan yang butuh penjemputan.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script>
// Namespace untuk menghindari konflik
window.Dashboard = window.Dashboard || {};

// Deklarasi revenueChart dalam namespace
if (!window.Dashboard.revenueChart) {
    window.Dashboard.revenueChart = null;
}

// Fungsi helper untuk membersihkan modal
function cleanupModals() {
    console.log('Cleaning up modals...');
    
    // Hapus semua backdrop
    $('.modal-backdrop').remove();
    
    // Reset body state
    $('body').removeClass('modal-open');
    $('body').css({
        'overflow': '',
        'padding-right': ''
    });
    
    // Hide semua modal
    $('.modal').removeClass('show').attr('aria-hidden', 'true').css('display', 'none');
    
    console.log('Modal cleanup completed');
}

// Fungsi untuk menampilkan struk dengan aman
function showStrukModal(strukContent) {
    console.log('Showing struk modal...');
    
    // Bersihkan modal terlebih dahulu
    cleanupModals();
    
    setTimeout(function() {
        // Set konten struk
        $('#strukPesananBody').html(strukContent);
        
        // Buat instance modal baru
        var strukModalEl = document.getElementById('modalStrukPesanan');
        var strukModal = new bootstrap.Modal(strukModalEl, {
            backdrop: 'static',
            keyboard: false
        });
        
        // Tampilkan modal
        strukModal.show();
        
        console.log('Struk modal displayed');
    }, 300); // Delay untuk memastikan cleanup selesai
}

$(document).ready(function() {
    // Cegah inisialisasi ganda
    if (!window.Dashboard.initialized) {
        console.log('Initializing dashboard...');
        initCharts();
        setupFilterButtons();
        $('.revenue-filter.active').trigger('click');
        window.Dashboard.initialized = true;
    } else {
        console.log('Dashboard already initialized, skipping...');
    }

    // Event handler untuk form tambah pesanan
    $('#formTambahPesanan').off('submit').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted...');
        
        var form = $(this);
        var formData = form.serialize() + '&from_dashboard_modal=1';
        var btn = form.find('button[type=submit]');
        var originalText = btn.text();
        
        // Disable button dan ubah text
        btn.prop('disabled', true).text('Menyimpan...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(res) {
                console.log('Order saved successfully:', res);
                
                btn.text('Tersimpan!').removeClass('btn-primary').addClass('btn-success');
                
                // Reset form
                form[0].reset();
                $(form).find('.is-invalid').removeClass('is-invalid');
                $(form).find('.invalid-feedback').remove();
                
                setTimeout(function() {
                    // Tutup modal tambah pesanan
                    var tambahModalEl = document.getElementById('modalTambahPesanan');
                    var tambahModal = bootstrap.Modal.getInstance(tambahModalEl);
                    
                    if (tambahModal) {
                        tambahModal.hide();
                    }
                    
                    // Event listener untuk ketika modal tambah tertutup
                    $('#modalTambahPesanan').off('hidden.bs.modal.strukShow').on('hidden.bs.modal.strukShow', function() {
                        $(this).off('hidden.bs.modal.strukShow'); // Remove event listener
                        
                        // Reset button
                        btn.prop('disabled', false)
                           .text(originalText)
                           .removeClass('btn-success')
                           .addClass('btn-primary');
                        
                        // Tampilkan struk jika ada
                        if (res.struk) {
                            showStrukModal(res.struk);
                        } else {
                            // Jika tidak ada struk, tampilkan pesan sukses
                            alert('Pesanan berhasil disimpan!');
                        }
                    });
                    
                }, 1500); // Delay untuk menampilkan pesan "Tersimpan!"
            },
            error: function(xhr, status, error) {
                console.error('Error saving order:', error);
                
                btn.prop('disabled', false).text(originalText);
                
                var msg = 'Gagal menyimpan pesanan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = 'Validasi gagal: ' + Object.values(xhr.responseJSON.errors).flat().join(', ');
                }
                
                alert(msg);
            }
        });
    });

    // Event handler untuk modal tambah pesanan ketika ditutup
    $('#modalTambahPesanan').on('hidden.bs.modal', function() {
        console.log('Modal tambah pesanan closed');
        cleanupModals();
        
        // Reset form jika belum di-reset
        var form = $('#formTambahPesanan');
        form[0].reset();
        $(form).find('.is-invalid').removeClass('is-invalid');
        $(form).find('.invalid-feedback').remove();
        
        // Reset button
        var btn = form.find('button[type=submit]');
        btn.prop('disabled', false)
           .text('Simpan Pesanan')
           .removeClass('btn-success')
           .addClass('btn-primary');
    });

    // Event handler untuk modal struk ketika ditutup
    $('#modalStrukPesanan').on('hidden.bs.modal', function() {
        console.log('Modal struk closed');
        cleanupModals();
        
        // Kosongkan konten struk
        $('#strukPesananBody').empty();
    });

    // Event handler untuk tombol close manual
    $('.btn-close, [data-bs-dismiss="modal"]').on('click', function() {
        var modal = $(this).closest('.modal');
        var modalId = modal.attr('id');
        var modalInstance = bootstrap.Modal.getInstance(modal[0]);
        
        if (modalInstance) {
            modalInstance.hide();
        }
        
        setTimeout(cleanupModals, 300);
    });
});

// Event listener global untuk membersihkan modal
$(window).on('beforeunload', function() {
    cleanupModals();
});

function initCharts() {
    console.log('Initializing charts...');
    const revenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');

    // Hancurkan chart sebelumnya jika ada
    if (window.Dashboard.revenueChart) {
        console.log('Destroying previous revenueChart...');
        window.Dashboard.revenueChart.destroy();
    }

    window.Dashboard.revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Revenue',
                data: @json($weeklyData),
                backgroundColor: 'rgba(28, 200, 138, 0.6)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Define valid statuses and their order (gunakan status yang konsisten dengan database dan label Indonesia)
    const validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
    const statusLabels = ['Menunggu', 'Sedang Diproses', 'Selesai', 'Dibatalkan'];
    const statusColors = ['#ffc107', '#0d6efd', '#198754', '#dc3545']; // kuning, biru, hijau, merah
    const filteredStatusData = {};
    
    // Inisialisasi semua status valid dengan 0
    validStatuses.forEach(status => {
        filteredStatusData[status] = 0;
    });
    
    // Isi dengan data aktual
    @foreach($orderStatusData as $status => $count)
        if (validStatuses.includes('{{ $status }}')) {
            filteredStatusData['{{ $status }}'] = {{ $count }};
        }
    @endforeach

    const statusCtx = document.getElementById('orderStatusPieChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: validStatuses.map(status => filteredStatusData[status]),
                backgroundColor: statusColors,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 10
                    }
                },
            }
        },
    });
}

function setupFilterButtons() {
    console.log('Setting up filter buttons...');
    let selectedRevenueType = 'total';

    $('.revenue-type-filter').on('click', function () {
        $('.revenue-type-filter').removeClass('active');
        $(this).addClass('active');
        selectedRevenueType = $(this).data('type');
        $('.revenue-filter.active').trigger('click');
    });

    $('.revenue-filter').on('click', function () {
        $('.revenue-filter').removeClass('active');
        $(this).addClass('active');
        const period = $(this).data('period');

        $.ajax({
            url: '{{ route("dashboard.stats") }}',
            type: 'GET',
            data: {
                type: 'revenue',
                period: period,
                revenue_type: selectedRevenueType
            },
            success: function (response) {
                console.log('Revenue data loaded:', response);
                const config = {
                    total: {
                        label: 'Total Pendapatan',
                        color: 'rgba(28, 200, 138, 0.6)',
                        borderColor: 'rgba(28, 200, 138, 1)'
                    },
                    unpaid: {
                        label: 'Belum Diterima',
                        color: 'rgba(246, 194, 62, 0.6)',
                        borderColor: 'rgba(246, 194, 62, 1)'
                    },
                    profit: {
                        label: 'Laba Bersih',
                        color: 'rgba(54, 185, 204, 0.6)',
                        borderColor: 'rgba(54, 185, 204, 1)'
                    }
                };

                // Pastikan chart ada sebelum update
                if (window.Dashboard.revenueChart) {
                    window.Dashboard.revenueChart.data.labels = response.labels;
                    window.Dashboard.revenueChart.data.datasets[0].data = response.data;
                    window.Dashboard.revenueChart.data.datasets[0].label = config[selectedRevenueType].label;
                    window.Dashboard.revenueChart.data.datasets[0].backgroundColor = config[selectedRevenueType].color;
                    window.Dashboard.revenueChart.data.datasets[0].borderColor = config[selectedRevenueType].borderColor;
                    window.Dashboard.revenueChart.update();
                    console.log('Revenue chart updated');
                } else {
                    console.error('revenueChart not initialized');
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to load revenue data:', error);
                alert('Gagal memuat data Revenue!');
            }
        });
    });
}

// Konfirmasi pesanan butuh penjemputan tanpa reload, modal hanya tutup jika sudah tidak ada notifikasi lagi
$(document).on('submit', '.butuh-jemput-form', function(e) {
    e.preventDefault();
    var form = $(this);
    var btn = form.find('button[type=submit]');
    btn.prop('disabled', true).text('Mengonfirmasi...');
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            // Hapus baris pesanan dari daftar
            var li = form.closest('li');
            li.slideUp(300, function() {
                li.remove();
                // Cek jika sudah tidak ada item lagi
                if ($('#butuhJemputModal .list-group-item').length === 0) {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('butuhJemputModal'));
                    if(modal) modal.hide();
                    // Sembunyikan notifikasi utama
                    $('.alert-warning:has([data-bs-target="#butuhJemputModal"])').remove();
                }
            });
        },
        error: function(xhr) {
            alert('Gagal mengonfirmasi pesanan.');
            btn.prop('disabled', false).text('Konfirmasi');
        }
    });
});
</script>
@endpush