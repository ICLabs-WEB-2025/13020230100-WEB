<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryApp - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')

    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <!-- Tambahkan tombol hamburger di header -->
<header class="navbar navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <button class="navbar-toggler position-absolute d-md-none" type="button" id="sidebarToggle">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Konten header lainnya -->
</header>

<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 d-none d-md-block">
            <h4 class="text-white">LaundryApp</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <i class="fas fa-users me-2"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('services*') ? 'active' : '' }}" href="{{ route('services.index') }}">
                    <i class="fas fa-concierge-bell me-2"></i> Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('orders*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    <i class="fas fa-clipboard-list me-2"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="fas fa-chart-bar me-2"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</nav>





            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('title')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('header-buttons')
                    </div>
                </div>

                @yield('content')
            </main>
        </div>
    </div>
    

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.createElement('div');
    sidebarOverlay.className = 'sidebar-overlay';
    document.body.appendChild(sidebarOverlay);
    
    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        sidebarOverlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
    });
    
    // Tutup sidebar saat klik overlay
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        sidebarOverlay.style.display = 'none';
    });
    
    // Tutup sidebar saat menu diklik (untuk mobile)
    if (window.innerWidth < 768) {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.style.display = 'none';
            });
        });
    }
    
    // Responsif saat resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.add('show');
            sidebarOverlay.style.display = 'none';
        } else {
            sidebar.classList.remove('show');
        }
    });
});
</script>
    @stack('scripts')
</body>
</html>