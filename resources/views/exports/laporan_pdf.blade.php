{{-- resources/views/exports/laporan_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        body { margin: 0; padding: 20px; }
        h2 { color: #1a237e; font-size: 16px; margin-bottom: 4px; }
        .info { color: #666; margin-bottom: 16px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a73e8; color: white; padding: 8px 6px; text-align: left; }
        td { padding: 6px; border-bottom: 1px solid #e8ecf0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .total-row td { background: #e8f0fe; font-weight: bold; border-top: 2px solid #1a73e8; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>LAPORAN PENJUALAN — TOKO GROSIR ONLINE</h2>
    <div class="info">
        Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s/d
                 {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }} |
        Status: {{ $status === 'semua' ? 'Semua' : ucfirst(str_replace('_',' ',$status)) }} |
        Dicetak: {{ now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Order</th>
                <th>Pembeli</th>
                <th>Alamat</th>
                <th>Total (Rp)</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ Str::limit($order->address, 30) }}</td>
                <td class="text-right">{{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ ucfirst(str_replace('_',' ',$order->status)) }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL ({{ $orders->count() }} transaksi):</td>
                <td class="text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>