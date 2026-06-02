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

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'              => 'Menunggu Pembayaran',
            'menunggu_verifikasi'  => 'Menunggu Verifikasi',
            'diproses'             => 'Sedang Diproses',
            'dikirim'              => 'Sedang Dikirim',
            'selesai'              => 'Selesai',
            default                => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'              => 'warning',
            'menunggu_verifikasi'  => 'info',
            'diproses'             => 'primary',
            'dikirim'              => 'secondary',
            'selesai'              => 'success',
            default                => 'dark',
        };
    }
}
