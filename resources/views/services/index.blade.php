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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="servicesTable" width="100%" cellspacing="0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="30%">Nama Layanan</th>
                                        <th width="30%">Harga per KG</th>
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
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
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