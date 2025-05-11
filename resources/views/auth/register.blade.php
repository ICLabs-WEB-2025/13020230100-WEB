@extends('layouts.auth')

@section('title', 'Register - Laundry-In')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- Left Section -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center text-white"
             style="background-color: #0d6efd;">
            <h1 class="fw-bold mb-3" style="font-family: 'Georgia', serif; font-size: 2.5rem;">Laundry-In</h1>
            <img src="{{ asset('images/laundry-detergent.png') }}" alt="Laundry Image" class="img-fluid mb-3" style="max-height: 400px; width: auto;">
            <p class="fw-semibold" style="font-size: 1.5rem;">Bersih, wangi, siap pakai</p>
        </div>

        <!-- Right Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light p-4 rounded-kolom-kanan">
            <div class="w-100" style="max-width: 450px;">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-3 fw-bold" style="color: #0d6efd; font-size: 1.5rem;">Daftar Akun Baru</h2>

                        @if($errors->any())
                            <div class="alert alert-danger py-2 mb-3">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li class="small">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label small fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control py-2 rounded-2 @error('name') is-invalid @enderror" 
                                       id="name" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control py-2 rounded-2 @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label small fw-semibold">Nomor Whatsapp</label>
                                <input type="tel" class="form-control py-2 rounded-2 @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" placeholder="Masukkan no whatsapp" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold">Password</label>
                                <input type="password" class="form-control py-2 rounded-2 @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Masukkan password" required>
                                @error('password')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label small fw-semibold">Konfirmasi Password</label>
                                <input type="password" class="form-control py-2 rounded-2" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Masukkan kembali password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-2 fw-semibold mb-3">
                                Daftar
                            </button>

                            <div class="text-center mt-3">
                                <p class="small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">Login disini</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('styles')
<style>
    html, body {
        height: 100%;
        margin: 0;
        overflow: auto;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container-fluid {
        height: 100%;
    }
    .min-vh-100 {
        min-height: 100vh;
    }
    .row {
        height: 100%;
    }
    .card {
        border: none;
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
    }
    .form-control {
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
    }
    .text-muted {
        color: #6c757d;
        font-style: italic;
    }
    a.text-decoration-none:hover {
        color: #0b5ed7 !important;
        text-decoration: underline !important;
    }

    @media (min-width: 992px) {
        .rounded-kolom-kanan {
            border-top-left-radius: 80px;
            border-bottom-left-radius: 80px;
        }
    }
</style>
@endsection