<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Ruangan;
use App\Models\Kategori;

class ruanganeeder extends Seeder
{
    public function run(): void
    {
        $meeting = Kategori::firstOrCreate(
            ['nama' => 'Meeting'],
            ['slug' => Str::slug('Meeting')]
        );
        $coworking = Kategori::firstOrCreate(
            ['nama' => 'Coworking'],
            ['slug' => Str::slug('Coworking')]
        );
        $event = Kategori::firstOrCreate(
            ['nama' => 'Event'],
            ['slug' => Str::slug('Event')]
        );
        $private = Kategori::firstOrCreate(
            ['nama' => 'Private'],
            ['slug' => Str::slug('Private')]
        );

        Ruangan::create([
            'kategori_id' => $meeting->id,
            'nama' => 'Ruang Meeting Elang',
            'gedung' => 'Gedung A',
            'lantai' => 'Lantai 2',
            'kapasitas' => 8,
            'harga_per_jam' => 50000,
            'deskripsi' => 'Ruang meeting nyaman dengan proyektor dan whiteboard.',
            'foto' => 'image/images.jpeg',
            'status' => 'aktif',
        ]);

        Ruangan::create([
            'kategori_id' => $meeting->id,
            'nama' => 'Ruang Meeting Garuda',
            'gedung' => 'Gedung A',
            'lantai' => 'Lantai 3',
            'kapasitas' => 12,
            'harga_per_jam' => 75000,
            'deskripsi' => 'Ruang meeting besar cocok untuk rapat tim.',
            'foto' => 'image/images1.jpeg',
            'status' => 'aktif',
        ]);

        Ruangan::create([
            'kategori_id' => $coworking->id,
            'nama' => 'Coworking Space Utama',
            'gedung' => 'Gedung B',
            'lantai' => 'Lantai 1',
            'kapasitas' => 30,
            'harga_per_jam' => 25000,
            'deskripsi' => 'Area kerja terbuka dengan wifi cepat dan colokan di tiap meja.',
            'foto' => 'image/images3.jpeg',
            'status' => 'aktif',
        ]);

        Ruangan::create([
            'kategori_id' => $coworking->id,
            'nama' => 'Coworking VIP',
            'gedung' => 'Gedung B',
            'lantai' => 'Lantai 2',
            'kapasitas' => 15,
            'harga_per_jam' => 40000,
            'deskripsi' => 'Area coworking lebih privat dengan AC dan sofa.',
            'foto' => 'image/images2.jpeg',
            'status' => 'aktif',
        ]);

        Ruangan::create([
            'kategori_id' => $event->id, 
            'nama' => 'Ballroom Serbaguna',
            'gedung' => 'Gedung C',
            'lantai' => 'Lantai 1',
            'kapasitas' => 100,
            'harga_per_jam' => 500000,
            'deskripsi' => 'Ruang besar untuk acara, seminar, atau gathering.',
            'foto' => 'image/images1.jpeg',
            'status' => 'aktif',
        ]);

        Ruangan::create([
            'kategori_id' => $private->id,
            'nama' => 'Ruang Privat Mawar',
            'gedung' => 'Gedung A',
            'lantai' => 'Lantai 4',
            'kapasitas' => 4,
            'harga_per_jam' => 35000,
            'deskripsi' => 'Ruang kecil privat, cocok untuk fokus kerja atau kelompok kecil.',
            'foto' => 'image/images.jpeg',
            'status' => 'aktif',
        ]);
    }
}