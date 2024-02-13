<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $title = "Login";
        return view('auth.login', compact('title'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->route('patient.index');
        }

        return back()->with('error', 'Email dan Password tidak sesuai');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function getTableNumberFromRole($role)
    {
        $tableNumbers = [
            'Admin Table 1' => 1,
            'Admin Table 2' => 2,
            'Admin Table 3' => 3,
        ];

        return $tableNumbers[$role] ?? null;
    }
}
