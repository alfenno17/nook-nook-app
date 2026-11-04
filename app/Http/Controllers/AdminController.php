<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;
use App\Models\Ruangan;
use App\Models\Kategori;
use App\Models\User;             
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ===== DASHBOARD ADMIN[cite: 7] =====
    public function index()
    {
        $today = now()->toDateString();

        $bookingAktif = Booking::with(['user', 'ruangan', 'pembayaran'])
            ->whereIn('status', ['menunggu_konfirmasi_admin', 'disetujui', 'menunggu_pembayaran', 'menunggu_verifikasi', 'dibayar'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $totalRuangan   = Ruangan::count();
        $bookingHariIni = Booking::where('tanggal', $today)->count();
        $totalPengguna  = User::count();
        $pendapatan     = Booking::whereIn('status', ['dibayar', 'disetujui'])->sum('harga_total') ?? 0;

        return view('dashboarda', compact(
            'bookingAktif',
            'totalRuangan',
            'bookingHariIni',
            'totalPengguna',
            'pendapatan'
        ));
    }

    public function manageUsers()
    {
        return view('dashboarda');
    }

    public function data()
    {
        $today = now()->toDateString();

        $bookingAktif = Booking::with(['user', 'ruangan', 'pembayaran'])
            ->whereIn('status', ['menunggu_konfirmasi_admin', 'disetujui', 'menunggu_pembayaran', 'menunggu_verifikasi', 'dibayar'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $totalRuangan   = Ruangan::count();
        $bookingHariIni = Booking::where('tanggal', $today)->count();
        $totalPengguna  = User::count();
        $pendapatan     = Booking::whereIn('status', ['dibayar', 'disetujui'])->sum('harga_total') ?? 0;

        return view('dashboarda', compact(
            'bookingAktif',
            'totalRuangan',
            'bookingHariIni',
            'totalPengguna',
            'pendapatan'
        ));
    }

    // ===== LAPORAN[cite: 7] =====
    public function laporan(Request $request)
    {
        $query = Booking::with(['user', 'ruangan', 'pembayaran']);

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $bookings = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $totalBooking    = $bookings->count();
        $totalPendapatan = $bookings->sum(function ($b) {
            return $b->harga_total ?? ($b->total_harga ?? 0);
        });
        $ruanganTerpakai = $bookings->pluck('ruangan_id')->unique()->count();

        return view('laporanadmin', compact(
            'totalBooking',
            'totalPendapatan',
            'ruanganTerpakai',
            'bookings'
        ));
    }

    // ===== MANAJEMEN RUANGAN ADMIN[cite: 7] =====
    public function ruanganadmin(Request $request) 
    {
        $query = Ruangan::with(['kategori', 'fasilitas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('gedung', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhereHas('kategori', function ($k) use ($search) {
                      $k->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $ruangan = $query->get();
        return view('ruanganadmin', compact('ruangan'));
    }

    public function createRuangan()
    {
        $kategoris = Kategori::all();
        $fasilitasAll = Fasilitas::all();
        return view('tambahruangan', compact('kategoris', 'fasilitasAll'));
    }

    public function storeRuangan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'gedung' => 'nullable|string|max:100',
            'lantai' => 'nullable|integer',
            'kapasitas' => 'required|integer|min:1',
            'harga_per_jam' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif,tersedia,tidak_tersedia',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:30720', // Batas ukuran diperbesar menjadi 5MB
            'fasilitas' => 'array',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('ruangan_foto', 'public');
            }
        }

        $ruangan = Ruangan::create([
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'gedung' => $request->gedung,
            'lantai' => $request->lantai,
            'kapasitas' => $request->kapasitas,
            'harga_per_jam' => $request->harga_per_jam,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'foto' => !empty($fotoPaths) ? json_encode($fotoPaths) : null,
        ]);

        if ($request->has('fasilitas')) {
            $ruangan->fasilitas()->sync($request->fasilitas);
        }

        return redirect()->route('ruanganadmin')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function editRuangan($id)
    {
        $ruangan = Ruangan::with('fasilitas')->findOrFail($id);
        $kategoris = Kategori::all();
        $fasilitasAll = Fasilitas::all();
        return view('editruangan', compact('ruangan', 'kategoris', 'fasilitasAll'));
    }

    public function updateRuangan(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'gedung' => 'nullable|string|max:100',
            'lantai' => 'nullable|integer',
            'kapasitas' => 'required|integer|min:1',
            'harga_per_jam' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif,tersedia,tidak_tersedia',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:30720', // Batas ukuran diperbesar menjadi 5MB
            'fasilitas' => 'array',
        ]);

        $data = $request->except(['foto', 'fasilitas']);

        if ($request->hasFile('foto')) {
            if ($ruangan->foto) {
                $oldFotos = json_decode($ruangan->foto, true);
                if (is_array($oldFotos)) {
                    foreach ($oldFotos as $old) {
                        if (Storage::disk('public')->exists($old)) {
                            Storage::disk('public')->delete($old);
                        }
                    }
                } elseif (is_string($ruangan->foto)) {
                    if (Storage::disk('public')->exists($ruangan->foto)) {
                        Storage::disk('public')->delete($ruangan->foto);
                    }
                }
            }

            $fotoPaths = [];
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('ruangan_foto', 'public');
            }
            $data['foto'] = json_encode($fotoPaths);
        }

        $ruangan->update($data);

        if ($request->has('fasilitas')) {
            $ruangan->fasilitas()->sync($request->fasilitas);
        } else {
            $ruangan->fasilitas()->detach();
        }

        return redirect()->route('ruanganadmin')->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function deleteRuangan($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        if ($ruangan->foto) {
            $oldFotos = json_decode($ruangan->foto, true);
            if (is_array($oldFotos)) {
                foreach ($oldFotos as $old) {
                    if (Storage::disk('public')->exists($old)) {
                        Storage::disk('public')->delete($old);
                    }
                }
            } elseif (is_string($ruangan->foto)) {
                if (Storage::disk('public')->exists($ruangan->foto)) {
                    Storage::disk('public')->delete($ruangan->foto);
                }
            }
        }

        $ruangan->delete();

        return redirect()->route('ruanganadmin')->with('success', 'Ruangan berhasil dihapus!');
    }

    // ===== MANAJEMEN PENGGUNA ADMIN[cite: 7] =====
    public function user(Request $request)
    {
        $query = User::with('roles')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhereHas('roles', function ($r) use ($search) {
                      $r->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $users = $query->get();

        return view('penggunaanadmin', compact('users'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('tambahpengguna', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.user')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('editpengguna', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.user')->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return redirect()->route('admin.user')->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.user')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function searchpengguna(Request $request)
    {
        return $this->user($request);
    }

    // ===== BOOKING, KONFIRMASI & VERIFIKASI[cite: 7] =====
    public function booking()   
    {
        return view('bookingadmin'); 
    }

    public function konfirmasiBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'menunggu_pembayaran']);

        return back()->with('success', 'Booking disetujui, user dapat melakukan pembayaran.');
    }

    public function verifikasi($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update(['status' => 'dibayar']);
        if ($booking->pembayaran) {
            $booking->pembayaran->update(['status' => 'dibayar']);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function verifikasiPembayaran($id)
    {
        return $this->verifikasi($id);
    }

    public function deleteBooking($id)
    {
        $booking = Booking::with('pembayaran')->findOrFail($id);

        if ($booking->pembayaran && $booking->pembayaran->bukti && Storage::disk('public')->exists($booking->pembayaran->bukti)) {
            Storage::disk('public')->delete($booking->pembayaran->bukti);
        }

        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus!');
    }

    // ===== LIHAT FILE BUKTI PEMBAYARAN[cite: 7] =====
    public function lihatBukti($id)
    {
        $booking = Booking::with('pembayaran')->findOrFail($id);

        if (!$booking->pembayaran || !$booking->pembayaran->bukti) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $booking->pembayaran->bukti);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        return response()->file($path);
    }
}