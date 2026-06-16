<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_proof',
        'processed_by',
        'address',
        'phone',

        // Kolom Midtrans
        'snap_token',
        'midtrans_transaction_id',
        'payment_method',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // ── Accessor status label ──────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'              => 'Menunggu Pembayaran',
            'menunggu_verifikasi'  => 'Menunggu Verifikasi',
            'diproses'             => 'Sedang Diproses',
            'dikirim'              => 'Sedang Dikirim',
            'selesai'              => 'Selesai',
            'dibatalkan'           => 'Dibatalkan',
            default                => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'              => 'warning',
            'menunggu_verifikasi'  => 'info',
            'diproses'             => 'primary',
            'dikirim'              => 'secondary',
            'selesai'              => 'success',
            'dibatalkan'           => 'danger',
            default                => 'dark',
        };
    }

    // ── Accessor metode pembayaran ──────────────────────────────
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'credit_card'    => 'Kartu Kredit',
            'bank_transfer'  => 'Transfer Bank',
            'echannel'       => 'Mandiri Bill',
            'bca_klikbca'    => 'KlikBCA',
            'bca_klikpay'    => 'BCA KlikPay',
            'cimb_clicks'    => 'CIMB Clicks',
            'danamon_online' => 'Danamon Online',
            'gopay'          => 'GoPay',
            'shopeepay'      => 'ShopeePay',
            'qris'           => 'QRIS',
            'akulaku'        => 'Akulaku',
            default          => ucfirst(str_replace('_', ' ', $this->payment_method ?? '-')),
        };
    }
}