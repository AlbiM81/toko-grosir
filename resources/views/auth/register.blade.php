{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Toko Grosir Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }
        .brand-panel {
            background: linear-gradient(160deg, #1a73e8 0%, #0f4c81 100%);
            border-radius: 20px 0 0 20px;
            color: white;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
        }
        .btn-register {
            background: linear-gradient(135deg, #1a73e8, #0f4c81);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(26,115,232,0.4);
            color: white;
        }
        .input-group-text { background: #f8f9fa; border-right: none; }
        .form-control { border-left: none; }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 1.2rem;
        }
        .feature-icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="register-card overflow-hidden">
                <div class="row g-0">

                    {{-- Panel Kiri (Brand) --}}
                    <div class="col-lg-5 brand-panel d-none d-lg-flex">
                        <div>
                            <div class="mb-4">
                                <span style="font-size:2.5rem;">🛒</span>
                                <h4 class="fw-bold mt-2 mb-1">Toko Grosir Online</h4>
                                <p class="opacity-75 small">Belanja grosir mudah, cepat, dan terpercaya</p>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                                <div>
                                    <div class="fw-semibold">Transaksi Aman</div>
                                    <div class="opacity-75 small">Pembayaran terverifikasi oleh tim kami</div>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="bi bi-truck"></i></div>
                                <div>
                                    <div class="fw-semibold">Pengiriman Terjamin</div>
                                    <div class="opacity-75 small">Lacak pesanan Anda secara real-time</div>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="bi bi-tags"></i></div>
                                <div>
                                    <div class="fw-semibold">Harga Grosir</div>
                                    <div class="opacity-75 small">Dapatkan harga terbaik langsung dari toko</div>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon"><i class="bi bi-headset"></i></div>
                                <div>
                                    <div class="fw-semibold">Layanan Pelanggan</div>
                                    <div class="opacity-75 small">Siap membantu setiap saat</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel Kanan (Form) --}}
                    <div class="col-lg-7">
                        <div class="p-4 p-md-5">
                            <h4 class="fw-bold mb-1">Buat Akun Baru</h4>
                            <p class="text-muted mb-4">Daftarkan diri sebagai pembeli dan mulai belanja</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                {{-- Nama Lengkap --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person text-muted"></i>
                                        </span>
                                        <input type="text"
                                               name="name"
                                               value="{{ old('name') }}"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="Masukkan nama lengkap"
                                               autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="contoh@email.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password"
                                               name="password"
                                               id="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Minimal 8 karakter">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePass">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- Password Strength Indicator --}}
                                    <div class="mt-2" id="strengthBar" style="display:none;">
                                        <div class="progress" style="height:4px;">
                                            <div class="progress-bar" id="strengthProgress" style="width:0%;transition:all 0.3s;"></div>
                                        </div>
                                        <small id="strengthText" class="text-muted"></small>
                                    </div>
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold small">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill text-muted"></i>
                                        </span>
                                        <input type="password"
                                               name="password_confirmation"
                                               class="form-control @error('password_confirmation') is-invalid @enderror"
                                               placeholder="Ulangi password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Syarat & Ketentuan --}}
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreeTnC" required>
                                        <label class="form-check-label small" for="agreeTnC">
                                            Saya menyetujui
                                            <a href="#" class="text-primary">Syarat & Ketentuan</a>
                                            dan
                                            <a href="#" class="text-primary">Kebijakan Privasi</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-register w-100 mb-3">
                                    <i class="bi bi-person-plus me-2"></i>Buat Akun Sekarang
                                </button>

                                <p class="text-center text-muted small mb-0">
                                    Sudah punya akun?
                                    <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk di sini</a>
                                </p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle password visibility
document.getElementById('togglePass').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        eye.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        eye.className = 'bi bi-eye';
    }
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const prog = document.getElementById('strengthProgress');
    const text = document.getElementById('strengthText');

    if (val.length === 0) { bar.style.display = 'none'; return; }
    bar.style.display = 'block';

    let strength = 0;
    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;

    const levels = [
        { w: '25%', c: 'bg-danger',  t: '🔴 Lemah' },
        { w: '50%', c: 'bg-warning', t: '🟡 Cukup' },
        { w: '75%', c: 'bg-info',    t: '🔵 Kuat' },
        { w: '100%',c: 'bg-success', t: '🟢 Sangat Kuat' },
    ];
    const lvl = levels[strength - 1] || levels[0];
    prog.style.width  = lvl.w;
    prog.className    = 'progress-bar ' + lvl.c;
    text.textContent  = lvl.t;
});
</script>
</body>
</html>