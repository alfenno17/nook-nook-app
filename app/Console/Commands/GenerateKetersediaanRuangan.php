<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateKetersediaanRuangan extends Command
{
    protected $signature = 'ruangan:generate';
    protected $description = 'Generate ketersediaan ruangan untuk 30 hari ke depan';

    public function handle()
    {
        $ruangan_ids = DB::table('ruangan')->pluck('id'); // Ambil semua ID ruangan secara dinamis
        $now = Carbon::now();

        // Loop untuk 30 hari ke depan (mulai hari ini H+0 sampai H+30)
        for ($day = 0; $day < 30; $day++) {
            $tanggalTarget = Carbon::now()->addDays($day)->toDateString();

            foreach ($ruangan_ids as $ruangan_id) {
                for ($i = 8; $i < 20; $i++) {
                    $jamMulai = sprintf('%02d:00:00', $i);
                    $jamSelesai = sprintf('%02d:00:00', $i + 1);

                    // Gunakan updateOrInsert agar tidak terjadi duplikasi jika data sudah ada
                    DB::table('ketersediaan_ruangan')->updateOrInsert(
                        [
                            'ruangan_id' => $ruangan_id,
                            'tanggal'    => $tanggalTarget,
                            'jam_mulai'  => $jamMulai,
                        ],
                        [
                            'jam_selesai' => $jamSelesai,
                            'status'      => 'tersedia', // status default
                            'created_at'  => $now,
                            'updated_at'  => $now,
                        ]
                    );
                }
            }
        }

        $this->info("Ketersediaan ruangan untuk 30 hari ke depan berhasil dibuat!");
    }
}
