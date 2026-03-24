<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // echo('vsa');exit();
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }
        $user = Auth::user();
        // 🔥 CREAR TOKEN
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        // $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada'
        ]);
    }
}
