<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Grosir Online - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            color: white;
            width: 250px;
            position: fixed;
            top: 0; left: 0;
        }
        .sidebar a { color: rgba(255,255,255,0.85); text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: rgba(255,255,255,0.1); border-radius: 8px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .navbar-top { background: #fff; border-bottom: 1px solid #dee2e6; padding: 12px 20px; margin-left: 250px; position: sticky; top: 0; z-index: 100; }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column p-3">
        <div class="text-center mb-4 mt-2">
            <h5 class="fw-bold mb-0">🛒 Toko Grosir</h5>
            <small class="opacity-75">{{ ucfirst(auth()->user()->role) }}</small>
        </div>

        <nav class="nav flex-column gap-1">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-link px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-tags me-2"></i> Kategori
                </a>
                <a href="{{ route('admin.karyawan.index') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-people me-2"></i> Karyawan
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-receipt me-2"></i> Transaksi
                </a>
                <a href="{{ route('admin.laporan') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-bar-chart me-2"></i> Laporan
                </a>
                <a href="{{ route('admin.pembeli.index') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-person-check me-2"></i> Data Pembeli
                </a>
            @elseif(auth()->user()->isKaryawan())
                <a href="{{ route('karyawan.dashboard') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('karyawan.products.index') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-box-seam me-2"></i> Produk
                </a>
                <a href="{{ route('karyawan.orders.index') }}" class="nav-link px-3 py-2">
                    <i class="bi bi-receipt me-2"></i> Pesanan
                </a>
            @endif
        </nav>

        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Top Navbar --}}
    <div class="navbar-top d-flex align-items-center justify-content-between">
        <h6 class="mb-0 text-muted">@yield('title')</h6>
        <span class="fw-semibold">{{ auth()->user()->name }}</span>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>