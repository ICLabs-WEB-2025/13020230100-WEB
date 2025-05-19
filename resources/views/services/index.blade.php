@extends('layouts.app')

@section('title', 'Daftar Layanan Laundry')

@section('content')
<div class="container mt-4">

    @if ($services->isEmpty())
        <div class="alert alert-info">
            Belum ada data layanan yang tersedia.
        </div>
    @else
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Harga per KG</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->description }}</td>
                        <td>Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
