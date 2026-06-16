{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Toko Grosir Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); min-height: 100vh; }
        .login-card { border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); }
        .btn-login {
            background: linear-gradient(135deg, #1a73e8, #0f4c81);
            border: none; color: white; padding: 12px;
            border-radius: 10px; font-weight: 700; transition: all 0.3s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(26,115,232,0.4); color: white; }
        .form-control:focus { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,0.15); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 login-card">
                <div class="card-body p-5">

                    {{-- Logo/Brand --}}
                    <div class="text-center mb-4">
                        <span style="font-size:2.5rem;">🛒</span>
                        <h4 class="fw-bold mt-2 mb-1">Toko Grosir Online</h4>
                        <p class="text-muted small">Masuk ke akun Anda</p>
                    </div>

                    {{-- Session Error --}}
                    @if(session('error'))
                        <div class="alert alert-danger small">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="email@contoh.com" autofocus>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-semibold small">Password</label>
                                @if(Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-primary">Lupa password?</a>
                                @endif
                            </div>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>

                        {{-- ↓↓ LINK KE REGISTER — INI YANG PENTING ↓↓ --}}
                        <p class="text-center text-muted small mb-0">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-primary fw-semibold">
                                Daftar di sini
                            </a>
                        </p>
                        {{-- ↑↑ SELESAI ↑↑ --}}

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>