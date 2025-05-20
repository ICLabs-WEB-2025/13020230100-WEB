<!-- resources/views/partials/sidebar.blade.php -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 d-none d-md-block">
            <h4 class="text-white">Laundry-In</h4>
        </div>
        <ul class="nav flex-column">
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                        <i class="fas fa-users me-2"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('services*') ? 'active' : '' }}" href="{{ route('services.index') }}">
                        <i class="fas fa-concierge-bell me-2"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('orders*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="fas fa-clipboard-list me-2"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('user/orders*') ? 'active' : '' }}" href="{{ route('user.orders') }}">
                        <i class="fas fa-clipboard-list me-2"></i> My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('user/profile*') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ \Illuminate\Support\Facades\Request::is('user/settings*') ? 'active' : '' }}" href="{{ route('user.settings') }}">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                </li>
            @endif
        </ul>

        <!-- Logout Section -->
        <div class="sidebar-footer mt-auto p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    .sidebar {
        min-height: 100vh;
        transition: all 0.3s;
    }

    .sidebar-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background-color: rgba(0, 0, 0, 0.1);
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            position: fixed;
            z-index: 1050;
            width: 250px;
            height: 100vh;
            left: -250px;
        }

        .sidebar.show {
            left: 0;
        }
    }
</style>