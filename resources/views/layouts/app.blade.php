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
    @stack('scripts')
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                @if(\Illuminate\Support\Facades\View::hasSection('title') && trim($__env->yieldContent('title')))
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('title')</h1>
                        @hasSection('header-buttons')
                            <div class="btn-toolbar mb-2 mb-md-0">
                                @yield('header-buttons')
                            </div>
                        @endif
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.createElement('div');
            sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(sidebarOverlay);

            // Initial sidebar state
            if (window.innerWidth >= 768) {
                sidebar.classList.add('show');
            }

            // Toggle sidebar
            sidebarToggle?.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                sidebarOverlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
            });

            // Close sidebar on overlay click
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                sidebarOverlay.style.display = 'none';
            });

            // Close sidebar on link click (mobile)
            if (window.innerWidth < 768) {
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', function () {
                        sidebar.classList.remove('show');
                        sidebarOverlay.style.display = 'none';
                    });
                });
            }

            // Handle resize
            window.addEventListener('resize', function () {
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
