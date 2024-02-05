<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showUpdatePasswordView() {
        $title = "Ubah Password";
        return view('settings.change-password', compact('title'));
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Kolom password saat ini harus diisi.',
            'password.required' => 'Kolom password wajib diisi.',
            'password.string' => 'Kolom password harus berupa string.',
            'password.min' => 'Kolom password harus memiliki setidaknya 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Kolom konfirmasi password wajib diisi.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password baru harus berbeda dengan password saat ini!');
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
