<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $authCredentials = [
            'name' => $credentials['username'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($authCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            Log::info('Login success', [
                'session_id_after' => $request->session()->getId(),
                'user_id' => Auth::id(),
            ]);

            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            $redirectParam = $request->input('redirect');

            if ($user && $user->hasRole('admin')) {
                return redirect()->intended($redirectParam ?? route('dashboarda'));
            }

            return redirect()->intended($redirectParam ?? '/');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}