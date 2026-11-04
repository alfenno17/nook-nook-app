<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Booking;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\BookingNotificationMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function ruangan(Request $request, Ruangan $ruangan)
    {
        return view('bookingruangan', compact('ruangan'));
    }

    public function sayap(Ruangan $ruangan)
    {
        return view('bookingsayap', compact('ruangan'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan booking.');
        }

        $validated = $request->validate([
            'ruangan_id'  => 'required|exists:ruangan,id',
            'tanggal'     => 'required|date|after_or_equal:today',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keperluan'   => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            
            // 1. CEK BENTROK BOOKING (Menggunakan logika interval jam yang presisi, abaikan yang dibatalkan/ditolak)
            $bentrokBooking = Booking::where('ruangan_id', $validated['ruangan_id'])
                ->where('tanggal', $validated['tanggal'])
                ->whereNotIn('status', ['dibatalkan', 'ditolak'])
                ->where(function ($query) use ($validated) {
                    $query->where('jam_mulai', '<', $validated['jam_selesai'])
                          ->where('jam_selesai', '>', $validated['jam_mulai']);
                })
                ->lockForUpdate()
                ->exists();

            // 2. CEK BENTROK KETERSEDIAAN (Menggunakan interval jam presisi)
            $bentrokKetersediaan = DB::table('ketersediaan_ruangan')
                ->where('ruangan_id', $validated['ruangan_id'])
                ->where('tanggal', $validated['tanggal'])
                ->where('jam_mulai', '<', $validated['jam_selesai'])
                ->where('jam_selesai', '>', $validated['jam_mulai'])
                ->where('status', 'dipesan')
                ->lockForUpdate()
                ->exists();

            if ($bentrokBooking || $bentrokKetersediaan) {
                return back()->withErrors(['jadwal' => 'Maaf, ruangan sudah dibooking pada jam tersebut.'])->withInput();
            }

            // 3. Hitung Total Harga
            $ruangan = Ruangan::findOrFail($validated['ruangan_id']);

            $jamMulai   = Carbon::parse($validated['jam_mulai']);
            $jamSelesai = Carbon::parse($validated['jam_selesai']);

            $durasiMenit = $jamMulai->diffInMinutes($jamSelesai);
            $durasiJam   = ceil($durasiMenit / 60);

            if ($durasiJam < 1) {
                $durasiJam = 1;
            }

            $totalHarga = $durasiJam * $ruangan->harga_per_jam;

            // 4. Simpan Data Booking Utama (Status 'menunggu_pembayaran')
            $booking = Booking::create([
                'user_id'     => Auth::id(),
                'ruangan_id'  => $validated['ruangan_id'],
                'tanggal'     => $validated['tanggal'],
                'jam_mulai'   => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'keperluan'   => $validated['keperluan'] ?? null,
                'harga_total' => $totalHarga,
                'status'      => 'menunggu_pembayaran',
            ]);

            // 5. Update/Insert Slot Jam ke Tabel ketersediaan_ruangan Menjadi 'dipesan'
            $startHour = (int) $jamMulai->format('H');
            $endHour   = (int) $jamSelesai->format('H');
            $now       = now();

            for ($i = $startHour; $i < $endHour; $i++) {
                $slotMulai   = sprintf('%02d:00:00', $i);
                $slotSelesai = sprintf('%02d:00:00', $i + 1);

                DB::table('ketersediaan_ruangan')->updateOrInsert(
                    [
                        'ruangan_id' => $validated['ruangan_id'],
                        'tanggal'    => $validated['tanggal'],
                        'jam_mulai'  => $slotMulai,
                    ],
                    [
                        'jam_selesai' => $slotSelesai,
                        'status'      => 'dipesan',
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]
                );
            }

            // 6. KIRIM EMAIL OTOMATIS (Tahap 1: Menunggu Pembayaran)
            $booking->load(['user', 'ruangan']);
            try {
                Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'menunggu_pembayaran'));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email booking: ' . $e->getMessage());
            }

            return redirect()->route('user.riwayat')
                ->with('success', 'Booking berhasil diajukan. Silakan lakukan pembayaran dan upload bukti transfer.');
        });
    }

    public function invoice($id)
    {
        $booking = Booking::with(['ruangan', 'user', 'pembayaran'])->findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('invoice', compact('booking'));
    }

    /**
     * Upload Bukti Pembayaran oleh User
     */
    public function uploadPembayaran(Request $request, $id)
    {
        $request->validate([
            'metode' => 'required|string',
            'bukti'  => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $booking = Booking::findOrFail($id);

        $path = $request->file('bukti')->store('bukti_pembayaran', 'public');

        Pembayaran::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'metode' => $request->metode,
                'bukti'  => $path,
                'jumlah' => $booking->harga_total,
                'status' => 'menunggu_verifikasi',
            ]
        );

        $booking->update(['status' => 'menunggu_verifikasi']);

        // KIRIM EMAIL OTOMATIS (Tahap 2: Menunggu Konfirmasi Admin)
        $booking->load(['user', 'ruangan']);
        try {
            Mail::to($booking->user->email)->send(new BookingNotificationMail($booking, 'menunggu_konfirmasi'));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email bukti pembayaran: ' . $e->getMessage());
        }

        return redirect()->route('user.riwayat')->with('success', 'Bukti pembayaran berhasil diunggah!');
    }
}
