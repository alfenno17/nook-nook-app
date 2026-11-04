<?php

namespace App\Models;

use App\Models\Pembayaran;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database MySQL
     */
    protected $table = 'booking';

    protected $fillable = [
        'kode',
        'user_id',
        'ruangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'harga_total',
        'status',
    ];

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'booking_id');
    }
}