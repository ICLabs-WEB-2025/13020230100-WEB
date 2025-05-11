@extends('layouts.auth')

@section('title', 'Login - Laundry-In')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- Bagian Kiri -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center text-white"
             style="background-color: #0d6efd;">
            <h1 class="fw-bold mb-4" style="font-family: 'Georgia', serif; font-size: 2.5rem;">Laundry-In</h1>
            <img src="{{ asset('images/laundry.png') }}" alt="Laundry Image" class="img-fluid mb-4" style="max-height: 400px; width: auto;">
            <p class="fw-semibold" style="font-size: 1.5rem;">Bersih, wangi, siap pakai</p>
        </div>

        <!-- Bagian Kanan -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light p-5 rounded-kolom-kanan">
            <div class="w-100" style="max-width: 500px;">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4 fw-bold" style="color: #0d6efd;">Login</h2>
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold" >Email</label>
                                <input type="email" class="form-control py-3 rounded-3" 
                                       id="email" name="email" placeholder="Masukkan email" 
                                       value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control py-3 rounded-3" 
                                       id="password" name="password" placeholder="Masukkan password" required>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold mb-3">
                                Login
                            </button>
                            
                            <div class="text-center mt-4">
                                <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Daftar disini!</a></p>
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
        overflow: hidden;
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
        transform: translateY(-5px);
    }
    .form-control {
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }
    .text-muted {
        color: #6c757d;
        font-style: italic;
    }
    a.text-decoration-none:hover {
        color: #0b5ed7 !important;
        text-decoration: underline !important;
    }

    /* Tambahkan lengkungan sisi kanan div kiri */
    @media (min-width: 992px) {
        .rounded-kolom-kanan {
            border-top-left-radius: 100px;
            border-bottom-left-radius: 100px;
        }
    }
</style>
@endsection
