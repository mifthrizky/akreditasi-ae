<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            $role = Auth::user()->role;
            $redirectRoute = match ($role) {
                'admin' => 'admin.dashboard',
                'dosen' => 'dosen.dashboard',
                'validator' => 'validator.dashboard',
                default => '/',
            };

            return redirect()->route($redirectRoute);
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak cocok, silahkan coba lagi.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
