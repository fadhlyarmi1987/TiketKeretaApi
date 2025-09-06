<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Semua user
    public function index()
    {
        return response()->json(User::all());
    }

    // Detail user by ID
    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json($user);
    }

    // Register user baru
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|string|email|unique:users,email',
            'password'      => 'required|string|min:6',
            'no_telp'       => 'nullable|string|max:20',
            'nik'           => 'nullable|string|max:20|unique:users,nik',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'role'          => 'user', // default otomatis
            'no_telp'       => $validated['no_telp'] ?? null,
            'nik'           => $validated['nik'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
        ]);

        return response()->json([
            'message' => 'User berhasil dibuat',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Hapus semua token lama biar single login
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('flutter-login')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
