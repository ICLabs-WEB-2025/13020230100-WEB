@extends('layouts.app')

@section('title', 'Daftar Layanan Laundry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Layanan Laundry</h6>
                    <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Layanan Baru
                    </a>
                </div>
                <div class="card-body">
                    @if ($services->isEmpty())
                        <div class="alert alert-info text-center">
                            Belum ada data layanan yang tersedia.
                        </div>
                    @else
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-bordered table-hover" id="servicesTable" width="100%" cellspacing="0" style="white-space: nowrap;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="30%">Nama Layanan</th>
                                        <th width="30%">Harga per KG</th>
                                        <th>Jumlah Pesanan</th>
                                        <th width="30%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $index => $service)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td>Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $service->orders->count() > 0 ? 'bg-warning' : 'bg-success' }}">
                                                    {{ $service->orders->count() }} pesanan
                                                </span>
                                                @if($service->orders->count() > 0)
                                                    <a href="{{ route('orders.index', ['service_id' => $service->id]) }}" class="btn btn-link btn-sm p-0 ms-1" title="Lihat pesanan layanan ini">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm mb-1">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    @if($service->orders->count() > 0)
                                                        <button type="button" class="btn btn-danger btn-sm mb-1" disabled title="Layanan masih digunakan pada {{ $service->orders->count() }} pesanan dan tidak dapat dihapus">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    @else
                                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin ingin menghapus layanan {{ $service->name }}?')">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
        $('#servicesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            }
        });
    });
</script>
@endsection
