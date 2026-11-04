<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';
    protected $fillable = ['nama'];

    public function ruangan()
    {
        return $this->belongsToMany(Ruangan::class, 'fasilitas', 'fasilitas_id', 'ruangan_id');
    }
}