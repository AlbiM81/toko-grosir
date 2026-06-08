<?php
// app/Exports/LaporanPenjualanExport.php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPenjualanExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    protected $tanggalMulai;
    protected $tanggalAkhir;
    protected $status;

    public function __construct($tanggalMulai = null, $tanggalAkhir = null, $status = null)
    {
        $this->tanggalMulai = $tanggalMulai ?? now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = $tanggalAkhir ?? now()->format('Y-m-d');
        $this->status       = $status ?? 'selesai';
    }

    public function view(): View
    {
        $query = Order::with(['user', 'orderDetails.product', 'processedBy'])
            ->whereBetween('created_at', [
                $this->tanggalMulai . ' 00:00:00',
                $this->tanggalAkhir . ' 23:59:59',
            ]);

        if ($this->status !== 'semua') {
            $query->where('status', $this->status);
        }

        $orders        = $query->orderBy('created_at')->get();
        $totalPenjualan = $orders->sum('total_price');
        $tanggalMulai  = $this->tanggalMulai;
        $tanggalAkhir  = $this->tanggalAkhir;
        $status        = $this->status;

        return view('exports.laporan_penjualan', compact(
            'orders',
            'totalPenjualan',
            'tanggalMulai',
            'tanggalAkhir',
            'status'
        ));
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Baris header (baris 1-3 adalah info, baris 4 adalah header tabel)
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1a237e']],
            ],
            4 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1a73e8'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}