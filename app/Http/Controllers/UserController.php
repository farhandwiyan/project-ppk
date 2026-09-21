<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'verifiedUsers' => User::where('status', 'verified')->count(),
        ]);
    }

    public function getAllUser(Request $request) {
        $search = $request->input('search');
        $filter = $request->input('filter');

        $users = User::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        })
        ->when($filter == 'unverified', function ($query, $filter) {
            return $query->where('status', '!=', 'verified');
        })
        ->when($filter == 'petugas', function ($query) {
            $query->where('role', 'petugas');
        })
        ->when($filter == 'user', function ($query) {
            $query->where('role', 'user');
        })
        ->paginate(50)->withQueryString();

        return view('admin.user', compact('users'));
    }

    public function verified(Request $request) {
        if (Auth::user()->role != 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak punya akses untuk melakukan verifikasi.');
        }

        $user = User::where('email', '=', $request->email)->firstOrFail();
        $user->update(['status' => 'verified']);

        return redirect()->back()->with('success', 'User berhasil di verifikasi');  
    }

    public function delete(Request $request) {
        if (Auth::user()->role != 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak punya akses untuk melakukan delete user.');
        }

        $user = User::where('email', '=', $request->email)->firstOrFail();
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil di hapus');
    }

    public function showCreateForm() {
        return view('users.create');
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:25',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // user yang sedang login
        $user = Auth::user();

        if ($user->role == 'admin') {
            User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'verified',
            ]);
        }

        if ($user->role == 'user' || $user->role == 'petugas') {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak punya akses untuk membuat user baru.');
        }


        return redirect()->route('users.index')->with('success', 'Berhasil membuat user baru!');
    }

    public function showUpdateForm() {
        return view('users.update', ['user' => Auth::user()]);
    }

    public function update(Request $request) {
        $user = User::findOrFail(Auth::user()->id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:25',
            'email' => 'required|string|email|unique:users',
            'password' => 'nullable|string|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Berhasil melakukan update data.');
    }
}
