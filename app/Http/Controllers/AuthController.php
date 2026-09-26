<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegisterForm() 
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:25',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // buat user 
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'unverified',
        ]);

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Silahkan tunggu konfirmasi akun dari admin.');
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) 
    {
        // validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // attempt login
        if (Auth::attempt(array_merge($credentials, ['status' => 'verified']))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->role != 'user') {
                if ($user->role == 'admin') {
                    return redirect()->intended('admin/dashboard')->with('success', 'Login berhasil!');
                } else {
                    return redirect()->intended('petugas/dashboard')->with('success', 'Login berhasil!');
                }
            }

            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        // jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) 
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout berhasil!');
    }
}
