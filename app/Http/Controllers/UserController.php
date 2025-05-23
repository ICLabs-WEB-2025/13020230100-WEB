<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan halaman profil user
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // Menampilkan halaman pengaturan user
    public function settings()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    // Memperbarui pengaturan user
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('user.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}