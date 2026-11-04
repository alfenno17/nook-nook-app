<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Override;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = [
        'kategori_id',
        'nama',
        'gedung',
        'lantai',
        'kapasitas',
        'harga_per_jam',
        'deskripsi',
        'foto',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_ruangan', 'ruangan_id', 'fasilitas_id');
    }
    
    public function getHashidAtribute()
    {
        return Hashids::encode($this->id);
        
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $decoded = Hashids::decode($value);

        if (!empty($decoded)) {
            return $this->where('id', $decoded[0])->firstOrFail();
        }

        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        abort(404);
    }
    
     public function getRouteKey()
    {
        return Hashids::encode($this->id);
    }
}