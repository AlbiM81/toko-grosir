{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')


@section('content')

{{-- ═══ STAT CARDS ═══ --}}
<div class="row g-3 mb-4">
    @php
        $stats = [
            [
                'label' => 'Total Penjualan',
                'value' => 'Rp ' . number_format($totalPenjualan, 0, ',', '.'),
                'icon'  => 'bi-currency-dollar',
                'color' => '#10b981',
                'bg'    => 'rgba(16,185,129,0.1)',
                'sub'   => $orderSelesai . ' transaksi selesai',
            ],
            [
                'label' => 'Total Order',
                'value' => number_format($totalOrder),
                'icon'  => 'bi-receipt',
                'color' => '#3b82f6',
                'bg'    => 'rgba(59,130,246,0.1)',
                'sub'   => $orderMenunggu . ' menunggu verifikasi',
            ],
            [
                'label' => 'Total Produk',
                'value' => number_format($totalProduk),
                'icon'  => 'bi-box-seam',
                'color' => '#f59e0b',
                'bg'    => 'rgba(245,158,11,0.1)',
                'sub'   => $stokHabis . ' produk habis',
            ],
            [
                'label' => 'Pembeli Terdaftar',
                'value' => number_format($totalPembeli),
                'icon'  => 'bi-people',
                'color' => '#8b5cf6',
                'bg'    => 'rgba(139,92,246,0.1)',
                'sub'   => $totalKaryawan . ' karyawan aktif',
            ],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 p-2" style="background:{{ $stat['bg'] }};">
                        <i class="bi {{ $stat['icon'] }} fs-4" style="color:{{ $stat['color'] }};"></i>
                    </div>
                    <i class="bi bi-arrow-up-right text-success small opacity-50"></i>
                </div>
                <div class="fw-bold fs-4 mb-1" style="color:#1e293b;">{{ $stat['value'] }}</div>
                <div class="text-muted small fw-semibold mb-1">{{ $stat['label'] }}</div>
                <div class="text-muted" style="font-size:0.75rem;">{{ $stat['sub'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ═══ STATUS ORDER BADGE ROW ═══ --}}
<div class="row g-2 mb-4 row-cols-2 row-cols-sm-3 row-cols-md-5">
    @php
        $statusCards = [
            ['label'=>'Pending',            'count'=>$orderPending,  'color'=>'#f59e0b', 'icon'=>'bi-clock'],
            ['label'=>'Menunggu Verifikasi','count'=>$orderMenunggu, 'color'=>'#3b82f6', 'icon'=>'bi-search'],
            ['label'=>'Diproses',           'count'=>$orderDiproses, 'color'=>'#8b5cf6', 'icon'=>'bi-gear'],
            ['label'=>'Dikirim',            'count'=>$orderDikirim,  'color'=>'#06b6d4', 'icon'=>'bi-truck'],
            ['label'=>'Selesai',            'count'=>$orderSelesai,  'color'=>'#10b981', 'icon'=>'bi-check-circle'],
        ];
    @endphp
    @foreach($statusCards as $sc)
    <div class="col">
        <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px; border-top:3px solid {{ $sc['color'] }} !important;">
            <i class="bi {{ $sc['icon'] }} mb-1" style="color:{{ $sc['color'] }}; font-size:1.3rem;"></i>
            <div class="fw-bold fs-5" style="color:{{ $sc['color'] }};">{{ $sc['count'] }}</div>
            <div class="text-muted" style="font-size:0.7rem;">{{ $sc['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ═══ ROW 1: Line Chart + Donut Chart ═══ --}}
<div class="row g-4 mb-4">
    {{-- Line Chart: Penjualan 7 Hari --}}
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0" style="color:#1e293b;">Tren Penjualan</h6>
                    <small class="text-muted">7 Hari Terakhir</small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary active" id="btn7Hari" onclick="switchChart('7hari')">7 Hari</button>
                    <button class="btn btn-sm btn-outline-secondary" id="btn12Bulan" onclick="switchChart('12bulan')">12 Bulan</button>
                </div>
            </div>
            <div class="card-body px-3 pb-3" style="height:280px;">
                <canvas id="chartPenjualan"></canvas>
            </div>
        </div>
    </div>

    {{-- Donut Chart: Status Order --}}
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">Status Pesanan</h6>
                <small class="text-muted">Distribusi semua order</small>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="height:280px;">
                <canvas id="chartStatus" style="max-height:180px;"></canvas>
                <div class="mt-3 w-100 px-2">
                    @foreach($chartStatusLabel as $i => $label)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:10px; height:10px; border-radius:50%; background:{{ $chartStatusColor[$i] }};"></div>
                            <small class="text-muted">{{ $label }}</small>
                        </div>
                        <small class="fw-semibold">{{ $chartStatusData[$i] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ ROW 2: Bar Chart Produk Terlaris + Stok Rendah ═══ --}}
<div class="row g-4 mb-4">
    {{-- Horizontal Bar: Produk Terlaris --}}
    <div class="col-xl-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold mb-0" style="color:#1e293b;">Produk Terlaris</h6>
                <small class="text-muted">Berdasarkan total qty terjual</small>
            </div>
            <div class="card-body px-3 pb-3" style="height:300px;">
                <canvas id="chartProduk"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel: Stok Rendah --}}
    <div class="col-xl-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between">
                <div>
                    <h6 class="fw-bold mb-0" style="color:#1e293b;">⚠️ Stok Rendah</h6>
                    <small class="text-muted">Perlu segera diisi ulang</small>
                </div>
                @if($stokHabis > 0)
                <span class="badge bg-danger">{{ $stokHabis }} habis</span>
                @endif
            </div>
            <div class="card-body p-0" style="overflow-y:auto; max-height:270px;">
                @forelse($produkStokRendah as $produk)
                <div class="d-flex align-items-center gap-3 px-4 py-2 border-bottom">
                    @if($produk->image)
                        <img src="{{ asset('storage/'.$produk->image) }}"
                             style="width:38px; height:38px; object-fit:cover; border-radius:8px;" alt="">
                    @else
                        <div class="rounded-2 bg-light d-flex align-items-center justify-content-center"
                             style="width:38px; height:38px;">
                            <i class="bi bi-box text-muted"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold small text-truncate">{{ $produk->name }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">{{ $produk->category?->name }}</div>
                    </div>
                    <div>
                        @if($produk->stock == 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($produk->stock <= 5)
                            <span class="badge bg-warning text-dark">{{ $produk->stock }}</span>
                        @else
                            <span class="badge bg-info">{{ $produk->stock }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-check-circle fs-2 text-success"></i>
                    <p class="small mt-2">Semua stok dalam kondisi aman</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ═══ ROW 3: Transaksi Terbaru ═══ --}}
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0" style="color:#1e293b;">Transaksi Terbaru</h6>
                    <small class="text-muted">8 transaksi terakhir masuk</small>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th class="px-4 py-3 text-muted small fw-semibold border-0">ORDER ID</th>
                                <th class="py-3 text-muted small fw-semibold border-0">PEMBELI</th>
                                <th class="py-3 text-muted small fw-semibold border-0">TOTAL</th>
                                <th class="py-3 text-muted small fw-semibold border-0">STATUS</th>
                                <th class="py-3 text-muted small fw-semibold border-0">TANGGAL</th>
                                <th class="py-3 text-muted small fw-semibold border-0">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orderTerbaru as $order)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="fw-semibold text-primary">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                             style="width:32px; height:32px; font-size:0.75rem; font-weight:700; color:#3b82f6;">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">{{ $order->user->name }}</div>
                                            <div class="text-muted" style="font-size:0.72rem;">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 fw-semibold">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill bg-{{ $order->status_badge }} bg-opacity-15 text-{{ $order->status_badge }}"
                                          style="font-size:0.72rem; padding:5px 12px;">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 text-muted small">
                                    {{ $order->created_at->format('d M Y') }}<br>
                                    <span style="font-size:0.7rem;">{{ $order->created_at->format('H:i') }}</span>
                                </td>
                                <td class="py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── DATA DARI LARAVEL ──────────────────────────────────────────────
const data7Hari = {
    labels: @json($chartLabel7Hari),
    penjualan: @json($chartPenjualan7),
    jumlah: @json($chartJumlahOrder),
};
const data12Bulan = {
    labels: @json($chartLabel12Bulan),
    penjualan: @json($chartPenjualan12),
};

// ── CHART 1: PENJUALAN (LINE) ──────────────────────────────────────
const ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
const gradientLine = ctxPenjualan.createLinearGradient(0, 0, 0, 250);
gradientLine.addColorStop(0, 'rgba(59,130,246,0.3)');
gradientLine.addColorStop(1, 'rgba(59,130,246,0.01)');

let chartPenjualan = new Chart(ctxPenjualan, {
    type: 'line',
    data: {
        labels: data7Hari.labels,
        datasets: [{
            label: 'Penjualan (Rp)',
            data: data7Hari.penjualan,
            borderColor: '#3b82f6',
            backgroundColor: gradientLine,
            borderWidth: 2.5,
            pointRadius: 5,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3b82f6',
            pointBorderWidth: 2,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                }
            }
        },
        scales: {
            y: {
                grid: { color: '#f1f5f9' },
                ticks: {
                    callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v.toLocaleString('id-ID')),
                    font: { size: 11 }
                }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// Toggle 7 hari / 12 bulan
function switchChart(mode) {
    const btn7 = document.getElementById('btn7Hari');
    const btn12 = document.getElementById('btn12Bulan');

    if (mode === '7hari') {
        chartPenjualan.data.labels   = data7Hari.labels;
        chartPenjualan.data.datasets[0].data  = data7Hari.penjualan;
        chartPenjualan.data.datasets[0].label = 'Penjualan (Rp)';
        btn7.classList.add('active'); btn12.classList.remove('active');
    } else {
        chartPenjualan.data.labels   = data12Bulan.labels;
        chartPenjualan.data.datasets[0].data  = data12Bulan.penjualan;
        chartPenjualan.data.datasets[0].label = 'Penjualan Bulanan (Rp)';
        btn7.classList.remove('active'); btn12.classList.add('active');
    }
    chartPenjualan.update();
}

// ── CHART 2: STATUS DONUT ──────────────────────────────────────────
new Chart(document.getElementById('chartStatus').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: @json($chartStatusLabel),
        datasets: [{
            data: @json($chartStatusData),
            backgroundColor: @json($chartStatusColor),
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { display: false },
        }
    }
});

// ── CHART 3: PRODUK TERLARIS (HORIZONTAL BAR) ─────────────────────
new Chart(document.getElementById('chartProduk').getContext('2d'), {
    type: 'bar',
    data: {
        labels: @json($chartProdukLabel),
        datasets: [{
            label: 'Terjual (unit)',
            data: @json($chartProdukData),
            backgroundColor: [
                '#3b82f6','#10b981','#f59e0b','#8b5cf6',
                '#06b6d4','#ef4444','#84cc16','#f97316'
            ],
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y', // Mengunci sumbu agar menjadi horizontal chart
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: { 
                    // Menggunakan ctx.parsed.x karena nilai data berada di sumbu mendatar
                    label: ctx => ctx.parsed.x + ' unit terjual' 
                }
            }
        },
        scales: {
            x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
            y: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
@endsection