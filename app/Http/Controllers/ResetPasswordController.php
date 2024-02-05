<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function forgotView()
    {
        $title = "Lupa Password";
        return view('auth.forgot', compact('title'));
    }

    public function forgotSend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Mohon masukkan format email yang benar.',
            'email.exists' => 'Email tidak ditemukan dalam database.',
        ]);

        $email = urlencode($request->email);
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(10);

        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $resetUrl = url("/reset-password?email={$email}&token={$token}");

        Mail::send('emails.reset-password', [
            'resetUrl' => $resetUrl,
        ], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email anda!');
    }


    public function resetView()
    {
        $title = "Reset Password";
        return view('auth.reset', compact('title'));
    }

    public function resetAction(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'Kolom password wajib diisi.',
            'password.string' => 'Kolom password harus berupa string.',
            'password.min' => 'Kolom password harus memiliki setidaknya 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Kolom konfirmasi password wajib diisi.',
        ]);

        $updatePassword = PasswordReset::where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if (!$updatePassword || Carbon::now()->gt($updatePassword->expires_at)) {
            return back()->with('error', 'Token kadaluarsa atau tidak valid!');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan!');
        }

        $user->update(['password' => Hash::make($request->password)]);

        PasswordReset::where(['email' => $request->email])->delete();

        return redirect('/login')->with('success', 'Password berhasil direset!');
    }
}
