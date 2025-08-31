<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if ($user->role === 'admin') {
                // Hapus semua session lama user
                DB::table('sessions')->where('user_id', $user->id)->delete();

                // Login user
                Auth::login($user);

                // Buat token baru
                $user->tokens()->delete();
                $token = $user->createToken('web-login')->plainTextToken;

                session(['api_token' => $token]);

                return redirect()->route('dashboard');
            }
        }


        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
