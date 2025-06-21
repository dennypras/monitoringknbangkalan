<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if (! $pengguna || ! Hash::check($request->password, $pengguna->password)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        return response()->json([
            'token' => $pengguna->createToken('api-token')->plainTextToken,
            'pengguna' => $pengguna,
        ]);
    }

    public function logout(Request $request)
    {
        $request->pengguna()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout sukses']);
    }
}
