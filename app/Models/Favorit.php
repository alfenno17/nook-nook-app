<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    use HasFactory;

    protected $table = 'favorit';

    protected $fillable = [
        'pengguna_id',
        'ruangan_id',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}