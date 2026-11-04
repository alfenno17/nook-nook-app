<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ===== ROUTE UMUM / GUEST =====
Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/home', function () {
    return view('index');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');

Route::get('/ruangan', [RuanganController::class, 'daftar'])->name('daftarruangan');
Route::get('/daftar-ruangan', [RuanganController::class, 'daftar'])->name('ruangan.daftar');
Route::get('/ruangan/{ruangan}', [RuanganController::class, 'detail'])->name('ruangan.detail');

Route::name('ruangan.')->group(function () {
    Route::get('/ruangan', [RuanganController::class, 'daftar'])->name('daftar');
});

Route::get('/syarat-ketentuan', function () {
    return view('syarat-ketentuan');
})->name('syarat.ketentuan');

Route::get('/invoice/{id}', [BookingController::class, 'invoice'])->name('booking.invoice');

// ===== ROUTE BOOKING & PEMBAYARAN =====
Route::get('/booking/ruangan/{ruangan}', [BookingController::class, 'ruangan'])->name('booking.ruangan');
Route::get('/booking/sayap/{id}', [BookingController::class, 'sayap'])->name('booking.sayap');

Route::get('/pembayaran/{id}', [PembayaranController::class, 'index'])->name('pembayaran');
Route::post('/pembayaran/{id}', [PembayaranController::class, 'bayar'])->name('pembayaran.bayar');
Route::get('/status-bayar/{id}', [PembayaranController::class, 'status'])->name('status.bayar');

// ===== ROUTE USER (Harus Login) =====
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/riwayat', [UserController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/user/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::get('/user/pengaturan', [UserController::class, 'pengaturan'])->name('user.pengaturan');

    // Booking & Upload Pembayaran
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/{id}/pembayaran', [BookingController::class, 'uploadPembayaran'])->name('pembayaran.store');

    // Pengaturan Akun
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::post('/account/logout-others', [AccountController::class, 'logoutOtherDevices'])->name('account.logout-others');
    Route::post('/account/deactivate', [AccountController::class, 'deactivate'])->name('account.deactivate');
    Route::delete('/account/destroy', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy.fallback');
});

// ===== ROUTE ADMIN (Khusus Admin) =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboarda');
    Route::get('/bookingAdmin', [AdminController::class, 'booking'])->name('admin.booking');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/penggunaan', [AdminController::class, 'user'])->name('admin.user');

    // Lihat File Bukti Pembayaran
    Route::get('/admin/booking/{id}/bukti', [AdminController::class, 'lihatBukti'])->name('admin.booking.bukti');

    // Verifikasi & Konfirmasi Booking
    Route::post('/admin/booking/{id}/verifikasi', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
    Route::post('/admin/booking/{id}/konfirmasi', [AdminController::class, 'konfirmasiBooking'])->name('admin.konfirmasi');
    Route::delete('/admin/booking/{id}', [AdminController::class, 'deleteBooking'])->name('admin.booking.delete');

    // Manajemen Ruangan Admin
    Route::get('/adminruangan', [AdminController::class, 'ruanganadmin'])->name('admin.ruangan');
    Route::get('ruanganadmin', [AdminController::class, 'ruanganadmin'])->name('ruanganadmin');
    Route::get('/admin/tambah', [AdminController::class, 'createRuangan'])->name('ruangan.create');
    Route::post('/adminruangan', [AdminController::class, 'storeRuangan'])->name('ruangan.store');
    Route::get('/ruangan/edit/{ruangan}', [AdminController::class, 'editRuangan'])->name('ruangan.edit');
    Route::put('/ruangan/update/{ruangan}', [AdminController::class, 'updateRuangan'])->name('ruangan.update');
    Route::delete('/ruangan/delete/{ruangan}', [AdminController::class, 'deleteRuangan'])->name('ruangan.delete');

    // Manajemen Pengguna Admin
    Route::get('/pengguna/tambah', [AdminController::class, 'createUser'])->name('admin.user.create');
    Route::post('/pengguna', [AdminController::class, 'storeUser'])->name('admin.user.store');
    Route::get('/pengguna/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::put('/pengguna/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/hapus/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/invoice/{id}', [UserController::class, 'invoice'])->name('invoice');
});

Route::get('/invoice/{id}', [UserController::class, 'invoice'])->name('invoice');


// ===== LOGOUT =====
Route::match(['get', 'post'], '/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');