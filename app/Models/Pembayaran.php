<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database MySQL (tanpa akhiran 's')
     */
    protected $table = 'pembayaran';

    protected $fillable = [
        'booking_id',
        'metode',
        'nomor_va',
        'bukti',
        'jumlah',
        'status',
        'dibayar_pada',
    ];

}