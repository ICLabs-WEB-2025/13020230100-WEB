<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry-In Laundry - Layanan Laundry Premium</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #f8f9fa;
            --accent-color: #ff6b6b;
            --dark-color: #343a40;
            --light-color: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 10px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
            font-weight: 500;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
            font-weight: 500;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1604176354204-9268737828e4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            text-align: center;
        }
        
        .hero-section h1 {
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .hero-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-card {
            padding: 30px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #eee;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .pricing-card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .pricing-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .pricing-body {
            padding: 30px;
        }
        
        .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .testimonial-card {
            padding: 30px;
            border-radius: 10px;
            background-color: var(--secondary-color);
            margin-bottom: 30px;
        }
        
        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }
        
        .cta-section {
            background-color: var(--primary-color);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .cta-section h2 {
            font-weight: 700;
            margin-bottom: 30px;
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 20px;
        }
        
        .social-icon {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            color: var(--accent-color);
        }
        
        .footer-links h5 {
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer-links ul {
            list-style: none;
            padding-left: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .copyright {
            border-top: 1px solid #495057;
            padding-top: 20px;
            margin-top: 40px;
        }
        
        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate {
            animation: fadeInUp 1s ease forwards;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section p {
                font-size: 1rem;
            }
            
            .navbar-nav {
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-soap me-2"></i>Laundry-In
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Harga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimoni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
                <div class="d-flex ms-lg-3 mt-3 mt-lg-0">
                    <a href="/login" class="btn btn-outline-primary me-2">Masuk</a>
                    <a href="/register" class="btn btn-primary">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <h1 class="animate">Layanan Laundry Premium di Depan Pintu Anda</h1>
            <p class="animate">Kami menjemput, membersihkan, dan mengantarkan pakaian Anda dengan penuh perhatian. Nikmati kenyamanan layanan laundry premium tanpa perlu keluar rumah.</p>
            <div class="animate">
                <a href="/register" class="btn btn-primary btn-lg me-2">Mulai Sekarang</a>
                <a href="#features" class="btn btn-outline-light btn-lg">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Mengapa Memilih Laundry-In?</h2>
                <p class="text-muted">Kami menyediakan layanan laundry terbaik dengan kualitas premium</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card animate">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h4>Penjemputan & Pengantaran Gratis</h4>
                        <p class="text-muted">Kami akan menjemput dan mengantarkan laundry Anda tepat di depan pintu gratis untuk pesanan di atas Rp300.000.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Kualitas Premium</h4>
                        <p class="text-muted">Kami menggunakan deterjen ramah lingkungan dan peralatan canggih untuk perawatan terbaik pakaian Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Proses Cepat</h4>
                        <p class="text-muted">Dapatkan laundry Anda kembali dalam 24 jam dengan layanan ekspres kami.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Garansi Kepuasan</h4>
                        <p class="text-muted">Tidak puas? Kami akan mencuci ulang pakaian Anda gratis atau mengembalikan uang Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h4>Ramah Lingkungan</h4>
                        <p class="text-muted">Kami menggunakan deterjen biodegradable dan mesin hemat energi untuk mengurangi dampak lingkungan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>Pelacakan Mudah</h4>
                        <p class="text-muted">Lacak pesanan Anda secara real-time melalui aplikasi mobile atau website kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Cara Kerja Kami</h2>
                <p class="text-muted">Mencuci pakaian tidak pernah semudah ini</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <h4 class="mt-4">1. Jadwalkan</h4>
                        <p class="text-muted">Pilih waktu penjemputan yang sesuai melalui aplikasi atau website kami.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                        <h4 class="mt-4">2. Penjemputan</h4>
                        <p class="text-muted">Kami akan menjemput laundry Anda di depan pintu pada waktu yang telah dijadwalkan.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-soap fa-2x"></i>
                        </div>
                        <h4 class="mt-4">3. Pencucian</h4>
                        <p class="text-muted">Tim ahli kami akan membersihkan pakaian Anda dengan penuh perhatian menggunakan produk premium.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                        <h4 class="mt-4">4. Pengantaran</h4>
                        <p class="text-muted">Kami mengantarkan pakaian bersih dan rapi kembali ke Anda dalam 24 jam.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-5" id="pricing">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Harga Transparan</h2>
                <p class="text-muted">Tidak ada biaya tersembunyi. Layanan berkualitas dengan harga terjangkau.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card animate">
                        <div class="pricing-header">
                            <h4 class="text-white">Cuci Lipat</h4>
                        </div>
                        <div class="pricing-body text-center">
                            <h2 class="price">Rp25.000</h2>
                            <p class="text-muted">per kg</p>
                            <ul class="list-unstyled my-4">
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Cuci & Lipat</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Deterjen Standar</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Selesai dalam 48 jam</li>
                                <li class="mb-3 text-muted"><i class="fas fa-times me-2"></i> Perawatan Noda</li>
                                <li class="mb-3 text-muted"><i class="fas fa-times me-2"></i> Deterjen Premium</li>
                            </ul>
                            <a href="/register" class="btn btn-outline-primary w-100">Pilih Paket</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card animate">
                        <div class="pricing-header bg-warning">
                            <h4 class="text-white">Cuci Premium</h4>
                        </div>
                        <div class="pricing-body text-center">
                            <h2 class="price">Rp40.000</h2>
                            <p class="text-muted">per kg</p>
                            <ul class="list-unstyled my-4">
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Cuci & Lipat</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Deterjen Premium</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Selesai dalam 24 jam</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Perawatan Noda</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Pelembut Pakaian</li>
                            </ul>
                            <a href="/register" class="btn btn-primary w-100">Pilih Paket</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card animate">
                        <div class="pricing-header">
                            <h4 class="text-white">Dry Cleaning</h4>
                        </div>
                        <div class="pricing-body text-center">
                            <h2 class="price">Rp50.000</h2>
                            <p class="text-muted">per item</p>
                            <ul class="list-unstyled my-4">
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Dry Cleaning Profesional</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Perawatan Noda</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Setrika/Uap</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Selesai dalam 48 jam</li>
                                <li class="mb-3"><i class="fas fa-check text-primary me-2"></i> Pengembalian dengan Hanger</li>
                            </ul>
                            <a href="/register" class="btn btn-outline-primary w-100">Pilih Paket</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light" id="testimonials">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Apa Kata Pelanggan Kami</h2>
                <p class="text-muted">Jangan hanya percaya kata kami. Ini pendapat pelanggan kami.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card animate">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="testimonial-img">
                            <div>
                                <h5 class="mb-0">Sarah Wijaya</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">"Laundry-In sangat menghemat waktu saya! Kualitasnya selalu bagus, dan saya suka bisa melacak pesanan secara real-time. Sangat direkomendasikan!"</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card animate">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Customer" class="testimonial-img">
                            <div>
                                <h5 class="mb-0">Budi Santoso</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">"Sebagai profesional yang sibuk, Laundry-In sangat membantu. Pakaian saya selalu kembali bersih dan rapi. Layanan ekspres sangat worth it!"</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card animate">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Customer" class="testimonial-img">
                            <div>
                                <h5 class="mb-0">Dewi Lestari</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">"Awalnya ragu, tapi Laundry-In membuat saya puas. Mereka merawat bahan-bahan delicate dengan baik, dan penjemputan/pengantaran gratis sangat praktis."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="contact">
        <div class="container">
            <h2>Siap Mencoba Laundry-In?</h2>
            <p class="mb-4">Bergabunglah dengan ribuan pelanggan puas yang menghemat 5+ jam setiap minggu dengan layanan kami.</p>
            <div class="d-flex justify-content-center flex-wrap">
                <a href="/register" class="btn btn-light btn-lg me-3 mb-3">Mulai Sekarang</a>
                <a href="tel:+1234567890" class="btn btn-outline-light btn-lg mb-3"><i class="fas fa-phone me-2"></i>Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-muted">Temukan jawaban atas pertanyaan umum tentang layanan kami</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    Bagaimana sistem penjemputan dan pengantaran bekerja?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Cukup jadwalkan waktu penjemputan melalui aplikasi atau website kami. Kurir kami akan datang ke lokasi yang Anda tentukan untuk mengambil laundry. Setelah dicuci, kami akan mengantarkannya kembali pada waktu yang Anda inginkan.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    Berapa lama waktu penyelesaian laundry?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Layanan standar kami membutuhkan waktu 48 jam. Untuk biaya tambahan, kami menyediakan layanan ekspres 24 jam. Dry cleaning biasanya membutuhkan waktu 48-72 jam.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    Apakah tersedia opsi pencucian ramah lingkungan?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Tersedia! Kami menggunakan deterjen biodegradable berbahan dasar tumbuhan yang lembut untuk kain dan lingkungan. Anda bisa memilih opsi ini saat memesan.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                    Bagaimana cara pembayarannya?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Kami menerima semua kartu kredit utama, PayPal, dan Apple Pay melalui sistem pembayaran online yang aman. Pembayaran diproses saat Anda melakukan pemesanan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h3 class="text-white mb-4"><i class="fas fa-soap me-2"></i>Laundry-In</h3>
                    <p class="text-muted">Layanan laundry premium yang diantarkan ke depan pintu Anda. Menghemat waktu Anda dengan merawat pakaian Anda.</p>
                    <div class="mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-0">
                    <div class="footer-links">
                        <h5>Tautan Cepat</h5>
                        <ul>
                            <li><a href="#home">Beranda</a></li>
                            <li><a href="#features">Fitur</a></li>
                            <li><a href="#pricing">Harga</a></li>
                            <li><a href="#testimonials">Testimoni</a></li>
                            <li><a href="#contact">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-0">
                    <div class="footer-links">
                        <h5>Layanan</h5>
                        <ul>
                            <li><a href="#">Cuci & Lipat</a></li>
                            <li><a href="#">Dry Cleaning</a></li>
                            <li><a href="#">Penghilangan Noda</a></li>
                            <li><a href="#">Jasa Jahit</a></li>
                            <li><a href="#">Perawatan Khusus</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-links">
                        <h5>Hubungi Kami</h5>
                        <ul class="text-muted">
                            <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Laundry No. 123, Jakarta</li>
                            <li><i class="fas fa-phone me-2"></i> (021) 1234-5678</li>
                            <li><i class="fas fa-envelope me-2"></i> halo@Laundry-In.com</li>
                        </ul>
                        <div class="mt-4">
                            <a href="/login" class="btn btn-outline-light me-2">Masuk</a>
                            <a href="/register" class="btn btn-light">Daftar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright text-center text-muted">
                <p class="mb-0">&copy; 2023 Laundry-In Laundry Service. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animateElements = document.querySelectorAll('.animate');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });
            
            animateElements.forEach(element => {
                element.style.opacity = 0;
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(element);
            });
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Navbar background change on scroll
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled', 'shadow-sm');
                } else {
                    navbar.classList.remove('navbar-scrolled', 'shadow-sm');
                }
            });
        });
    </script>
</body>
</html>