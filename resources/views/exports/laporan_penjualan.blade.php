{{-- resources/views/exports/laporan_penjualan.blade.php --}}
<table>
    {{-- Header Laporan --}}
    <tr>
        <td colspan="8" style="font-weight:bold;font-size:14pt;color:#1a237e;">
            LAPORAN PENJUALAN — TOKO GROSIR ONLINE
        </td>
    </tr>
    <tr>
        <td colspan="8">
            Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="8">
            Status: {{ $status === 'semua' ? 'Semua Status' : ucfirst(str_replace('_',' ',$status)) }}
            &nbsp;|&nbsp; Dicetak: {{ now()->format('d M Y H:i') }}
        </td>
    </tr>
    <tr></tr>

    {{-- Header Tabel --}}
    <tr>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">No</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">No. Order</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">Nama Pembeli</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">Email</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">Alamat</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">Total (Rp)</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">Status</td>
        <td style="font-weight:bold;background-color:#1a73e8;color:#ffffff;">Tanggal</td>
    </tr>

    {{-- Data Transaksi --}}
    @foreach($orders as $i => $order)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $order->user->name }}</td>
        <td>{{ $order->user->email }}</td>
        <td>{{ $order->address }}</td>
        <td>{{ number_format($order->total_price, 0, ',', '.') }}</td>
        <td>{{ ucfirst(str_replace('_',' ',$order->status)) }}</td>
        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
    </tr>
    @endforeach

    {{-- Baris Total --}}
    <tr>
        <td colspan="5" style="font-weight:bold;text-align:right;">TOTAL PENJUALAN:</td>
        <td style="font-weight:bold;">{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        <td colspan="2">{{ $orders->count() }} transaksi</td>
    </tr>
</table>