<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Halaman Login
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Halaman Register
    |--------------------------------------------------------------------------
    */

    public function register()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | Proses Register
    |--------------------------------------------------------------------------
    */

    public function registerStore(Request $request)
    {
        $request->validate([

            'name' => 'required|max:100',

            'email' => 'required|email|unique:users,email',

            'nim' => 'required|unique:users,nim',

            'prodi' => 'required',

            'password' => 'required|min:6|confirmed'

        ]);

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'nim' => $request->nim,

            'prodi' => $request->prodi,

            'password' => Hash::make($request->password)

        ]);

        return redirect()
                ->route('login')
                ->with('success','Register berhasil. Silakan login.');

    }

    /*
    |--------------------------------------------------------------------------
    | Proses Login
    |--------------------------------------------------------------------------
    */

    public function loginStore(Request $request)
    {
        $request->validate([

            'email' => 'required|email',

            'password' => 'required'

        ]);

        if(Auth::attempt([

            'email' => $request->email,

            'password' => $request->password

        ], $request->remember))

        {

            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Selamat datang, ' . Auth::user()->name . ' 👋');

        }

        return back()

            ->withInput()

            ->withErrors([

                'email' => 'Email atau Password salah.'

            ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }
}