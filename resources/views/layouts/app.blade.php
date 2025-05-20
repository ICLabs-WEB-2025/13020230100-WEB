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
    
    <style>
        /* Tambahkan CSS ini */
        .main-content-wrapper {
            min-height: calc(100vh - 56px); /* Sesuaikan dengan tinggi header */
            display: flex;
            flex-direction: column;
        }
        
        .centered-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Pusatkan vertikal */
        }
        
        .dashboard-card {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto; /* Pusatkan horizontal */
            padding: 20px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content-wrapper">
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

                <div class="centered-content">
                    <div class="dashboard-card">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script yang sudah ada -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script sidebar yang sudah ada
        });
    </script>
    @stack('scripts')
</body>
</html>