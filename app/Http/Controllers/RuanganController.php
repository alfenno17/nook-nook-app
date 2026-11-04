<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    public function daftar(Request $request)
    {
        $query = Ruangan::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('gedung', 'like', '%' . $request->search . '%');
            });
        }


        if ($request->filled('category')) {
            $query->join('kategori', 'ruangan.kategori_id', '=', 'kategori.id')
                  ->where('kategori.nama', $request->category)
                  ->select('ruangan.*');
        }

        $ruangan = $query->get();

        return view('daftarruangan', compact('ruangan'));
    }

    public function detail(Request $request, Ruangan $ruangan)
    {
        $room = $ruangan;

        $fasilitas = DB::table('fasilitas_ruangan')
            ->join('fasilitas', 'fasilitas.id', '=', 'fasilitas_ruangan.fasilitas_id')
            ->where('fasilitas_ruangan.ruangan_id', $ruangan->id)
            ->select('fasilitas.*')
            ->get();

        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $dipesan = DB::table('ketersediaan_ruangan')
            ->where('ruangan_id', $ruangan->id)
            ->where('tanggal', $tanggal)
            ->where('status', 'dipesan')
            ->pluck('jam_mulai')
            ->toArray();

        $ketersediaan = [];
        for ($i = 8; $i < 20; $i++) {
            $jamMulai   = sprintf('%02d:00:00', $i);
            $jamSelesai = sprintf('%02d:00:00', $i + 1);

            $ketersediaan[] = (object) [
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'status'      => in_array($jamMulai, $dipesan) ? 'dipesan' : 'tersedia',
            ];
        }

        return view('detailruangan', compact('room', 'fasilitas', 'ketersediaan', 'tanggal'));
    }
}
