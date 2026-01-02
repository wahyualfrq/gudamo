<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_pengurus' => 'required',
            'email' => 'required|',
            'password' => 'required|min:6'
        ]);

        $pengurus = Pengurus::create([
            'nama_pengurus' => $data['nama_pengurus'],
            'email_pengurus' => $data['email'],
            'password_pengurus' => Hash::make($data['password'])
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Register berhasil',
            'data' => $pengurus
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $pengurus = Pengurus::where('email_pengurus', $validated['email'])->first();

        if (!$pengurus || !Hash::check($validated['password'], $pengurus->password_pengurus)) {
            return response()->json([
                'status' => 401,
                'message' => 'Email atau Password salah'
            ]);
        }

        $token = $pengurus->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Login berhasil',
            'token' => $token
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout berhasil'
        ]);
    }
}
