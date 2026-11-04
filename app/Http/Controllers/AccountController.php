<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Update profil user (nama, email, no HP, bio).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio'   => ['nullable', 'string', 'max:500'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->bio   = $validated['bio'] ?? null;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Nonaktifkan akun user sementara.
     */
    public function deactivate(Request $request)
    {
        $user = Auth::user();
        $user->is_active = false;
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Akun Anda telah dinonaktifkan sementara.');
    }

    /**
     * Hapus akun user secara permanen.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/')->with('success', 'Akun Anda telah dihapus.');
    }

    /**
     * Update password user.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'Password lama yang Anda masukkan salah.',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Logout dari semua perangkat lain.
     */
    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'password' => ['nullable'],
        ]);

        Auth::logoutOtherDevices($request->password ?? null);
        return back()->with('success', 'Berhasil logout dari perangkat lain.');
    }
}