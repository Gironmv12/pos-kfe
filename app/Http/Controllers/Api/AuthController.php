<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        //retornar el rol y token del usuario
        return response()->json([
            'message'=> 'Inicio de sesión exitoso',
            'nombre' => $usuario->nombre,
            'rol' => $usuario->rol,
            'token' => $usuario->createToken('API Token')->plainTextToken,
        ]);

    }
}
