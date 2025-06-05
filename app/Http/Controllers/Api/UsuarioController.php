<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    //crear un usuario
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:empleado,administrador',
        ]);

        $usuarios = Usuario::create([
            'nombre' => $validate['nombre'],
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
            'rol' => $validate['rol'],
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuarios,
        ], 201);
    }

    //obtener todos los usuarios
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json([
            'usuarios' => $usuarios,
        ], 200);
    }

    //actualizar un usuario
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:usuarios,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'rol' => 'sometimes|required|in:empleado,administrador',
        ]);

        $usuario = Usuario::findOrFail($id);

        if (isset($validate['password'])) {
            $validate['password'] = bcrypt($validate['password']);
        }

        $usuario->update($validate);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'usuario' => $usuario->makeHidden(['password']), // Oculta la contraseña en la respuesta
        ], 200);
    }

    //eliminar un usuario
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return response()->json([
            'message' => 'Usuario eliminado exitosamente',
        ], 200);
    }
}
