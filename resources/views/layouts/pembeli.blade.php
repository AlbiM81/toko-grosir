<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛒 Toko Grosir Online - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * {
            font-family: 'Figtree', sans-serif;
        }

        :root {
            --primary-color: #1a237e;
            --primary-light: #283593;
            --accent-color: #ff6f00;
            --text-light: #666;
        }

        body {
            background: #f8f9fa;
            color: var(--text-light);
        }

        /* Navbar Styling */
        .navbar-pembeli {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-pembeli .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .navbar-pembeli .nav-link:hover {
            color: #fff !important;
        }

        .navbar-pembeli .nav-link.active {
            color: #fff !important;
        }

        .navbar-pembeli .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-color);
        }

        .logo-brand {
            font-size: 24px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .logo-brand:hover {
            color: #fff;
            text-decoration: none;
        }

        .logo-badge {
            background: var(--accent-color);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
            font-weight: 600;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        /* Main Content */
        .main-wrapper {
            min-height: calc(100vh - 200px);
            padding: 30px 0;
        }

        /* Cards */
        .card {
            border: none !important;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12) !important;
            transform: translateY(-2px);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
        }

        .alert-success {
            border-left-color: #28a745;
            background: #f1f9f6;
            color: #155724;
        }

        .alert-danger {
            border-left-color: #dc3545;
            background: #fef5f5;
            color: #721c24;
        }

        .alert-warning {
            border-left-color: #ffc107;
            background: #fffbf0;
            color: #856404;
        }

        .alert-info {
            border-left-color: #17a2b8;
            background: #f0f7fb;
            color: #0c5460;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 35, 126, 0.3);
            background: linear-gradient(135deg, #151d6d 0%, #212f7d 100%);
        }

        .btn-accent {
            background: var(--accent-color);
            border: none;
            color: white;
        }

        .btn-accent:hover {
            background: #e65100;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 111, 0, 0.3);
            color: white;
        }

        /* Product Grid */
        .product-card {
            position: relative;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .product-price {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 18px;
        }

        /* Footer */
        .footer {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: rgba(255, 255, 255, 0.9);
            margin-top: 50px;
            padding: 40px 0 20px;
        }

        .footer-section h6 {
            color: white;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .footer-section a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 30px;
            padding-top: 20px;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 30px;
        }

        .breadcrumb-item {
            color: var(--text-light);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        /* Pagination */
        .pagination {
            gap: 5px;
        }

        .page-link {
            border: 1px solid #dee2e6;
            color: var(--primary-color);
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 80px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 30px;
        }

        /* Badge */
        .badge-success {
            background: #28a745;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .badge-primary {
            background: var(--primary-color);
        }

        .badge-info {
            background: #17a2b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-pembeli {
                padding: 10px 0;
            }

            .logo-brand {
                font-size: 20px;
            }

            .user-menu {
                gap: 10px;
            }

            .main-wrapper {
                padding: 20px 0;
            }

            .navbar-pembeli .nav-link.active::after {
                bottom: -10px;
            }
        }

        @media (max-width: 576px) {
            .logo-badge {
                font-size: 10px;
                padding: 2px 6px;
            }

            .user-profile span {
                display: none;
            }
        }
    </style>
</head>
<body>
    {{-- Navigation Bar --}}
    <nav class="navbar navbar-pembeli navbar-expand-lg">
        <div class="container-xl">
            <a href="{{ route('pembeli.dashboard') }}" class="logo-brand">
                <i class="bi bi-shop"></i>
                <span>Toko Grosir</span>
                <span class="logo-badge">ONLINE</span>
            </a>

            <button class="navbar-toggler btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavigation">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active' : '' }}"
                           href="{{ route('pembeli.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pembeli.products.index') ? 'active' : '' }}"
                           href="{{ route('pembeli.products.index') }}">
                            <i class="bi bi-shop me-1"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pembeli.orders.index') ? 'active' : '' }}"
                           href="{{ route('pembeli.orders.index') }}">
                            <i class="bi bi-receipt me-1"></i> Pesanan Saya
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('pembeli.cart.index') ? 'active' : '' }}"
                           href="{{ route('pembeli.cart.index') }}">
                            <i class="bi bi-cart3 me-1"></i> Keranjang
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-link nav-link dropdown-toggle user-profile" id="userMenuDropdown" data-bs-toggle="dropdown">
                            <div class="avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle me-2"></i> Profile Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('pembeli.orders.index') }}">
                                    <i class="bi bi-receipt me-2"></i> Riwayat Pesanan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="container-xl mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <strong>Perhatian!</strong> {{ session('warning') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    <strong>Informasi!</strong> {{ session('info') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <div class="main-wrapper">
        <div class="container-xl">
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container-xl">
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 footer-section">
                    <h6><i class="bi bi-shop me-2"></i>Toko Grosir</h6>
                    <p style="color: rgba(255,255,255,0.7); font-size: 14px;">
                        Toko Grosir Online menyediakan berbagai produk berkualitas dengan harga terbaik untuk kebutuhan Anda.
                    </p>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 footer-section">
                    <h6>Kategori</h6>
                    <a href="{{ route('pembeli.products.index') }}">Semua Produk</a>
                    @php
                        $categories = \App\Models\Category::limit(4)->get();
                    @endphp
                    @foreach($categories as $category)
                        <a href="{{ route('pembeli.products.index', ['category' => $category->id]) }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <div class="col-md-3 col-sm-6 footer-section">
                    <h6>Akun Saya</h6>
                    <a href="{{ route('pembeli.dashboard') }}">Dashboard</a>
                    <a href="{{ route('pembeli.cart.index') }}">Keranjang Saya</a>
                    <a href="{{ route('pembeli.orders.index') }}">Pesanan Saya</a>
                    <a href="{{ route('profile.edit') }}">Profil</a>
                </div>

                <div class="col-md-3 col-sm-6 footer-section">
                    <h6>Hubungi Kami</h6>
                    <a href="tel:+628123456789"><i class="bi bi-telephone me-2"></i>+62 812 3456 789</a>
                    <a href="mailto:info@tokogrosir.com"><i class="bi bi-envelope me-2"></i>info@tokogrosir.com</a>
                    <p style="color: rgba(255,255,255,0.7); font-size: 14px; margin-top: 15px;">
                        <i class="bi bi-geo-alt me-2"></i>Jl. Raya No. 123, Jakarta, Indonesia
                    </p>
                </div>
            </div>

            <div class="footer-divider">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0" style="font-size: 14px;">
                            &copy; 2026 Toko Grosir Online. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; margin-right: 20px;">Kebijakan Privasi</a>
                        <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none;">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
