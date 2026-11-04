<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Hitung booking aktif (status transaksi yang masih berjalan)
        $bookingAktif = Booking::where('user_id', $user->id)
                            ->whereIn('status', [
                                'menunggu_pembayaran', 
                                'menunggu_verifikasi', 
                                'menunggu_konfirmasi_admin', 
                                'disetujui'
                            ])
                            ->count();

        $totalRiwayat = Booking::where('user_id', $user->id)->count();

        // 5 Transaksi Terakhir untuk Tabel Dashboard
        $riwayat_booking = Booking::where('user_id', $user->id)
                                ->with('ruangan')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        $statistik = [
            ['label' => 'Booking Aktif', 'jumlah' => $bookingAktif],
            ['label' => 'Riwayat', 'jumlah' => $totalRiwayat],
            ['label' => 'Favorit', 'jumlah' => 0],
        ];

        return view('dashboard', compact(
            'user',
            'statistik',
            'riwayat_booking',
            'bookingAktif',
            'totalRiwayat'
        ));
    }

    public function riwayat()
    {
        $user = Auth::user();

        $riwayat = Booking::where('user_id', $user->id)
                        ->with('ruangan')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        $totalRiwayat = Booking::where('user_id', $user->id)->count();
        $riwayat_booking = $riwayat;

        return view('riwayat', compact('riwayat', 'totalRiwayat', 'riwayat_booking', 'user'));
    }

    public function profil()
    {
        $user = Auth::user();
        $totalRiwayat = Booking::where('user_id', $user->id)->count();
        return view('profil', compact('user', 'totalRiwayat'));
    }

    public function pengaturan()
    {
        $user = Auth::user();
        return view('pengaturan', compact('user'));
    } 

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio'   => 'nullable|string|max:500',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'bio'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function invoice($id)
    {
        $booking = Booking::with(['ruangan', 'user'])->findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('invoice', compact('booking'));
    }
}
