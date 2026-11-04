<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan'; // Nama tabel di database[cite: 25]

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
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Accessor otomatis mendeteksi fasilitas dari teks kolom deskripsi
    public function getFasilitasAttribute()
    {
        $deskripsi = strtolower($this->deskripsi ?? '');
        $daftarFasilitas = ['AC', 'Wi-Fi', 'Proyektor', 'Monitor', 'Whiteboard', 'Meja & Kursi', 'Sound System'];
        $hasil = [];

        foreach ($daftarFasilitas as $f) {
            if (str_contains($deskripsi, strtolower($f))) {
                $hasil[] = $f;
            }
        }

        return empty($hasil) ? ['Wi-Fi', 'AC', 'Meja & Kursi'] : $hasil;
    }
}