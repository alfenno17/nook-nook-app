<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['nama', 'deskripsi'];

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_kategori_id');
    }
}