@extends('layouts.app')

@section('title', 'Buat Layanan Baru')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Buat Layanan Baru</h4>
        </div>
        
        <div class="card-body">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label required">Nama Layanan</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price_per_kg" class="form-label required">Harga per Kg</label>
                    <input type="number" step="0.01" min="0" 
                           class="form-control @error('price_per_kg') is-invalid @enderror" 
                           id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg') }}" required>
                    @error('price_per_kg')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Layanan
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
