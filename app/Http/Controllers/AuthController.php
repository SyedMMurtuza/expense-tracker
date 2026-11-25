<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth');
    }

    public function RegisterForm(Request $request)

    {
        // dd($request->all());
        $data = $request->validate([
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'full_name' => $data['full_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
        ]);

        return redirect()->route('login')->with('success', 'Account has been created, Now you can Login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|max:255',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email or Password is incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('auth');
    }
}
