<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Meeting', 'slug' => 'meeting'],
            ['nama' => 'Coworking', 'slug' => 'coworking'],
            ['nama' => 'Event', 'slug' => 'event'],
            ['nama' => 'Private', 'slug' => 'private'],
            ['nama' => 'Studio', 'slug' => 'studio'],
            ['nama' => 'Classroom', 'slug' => 'classroom'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}