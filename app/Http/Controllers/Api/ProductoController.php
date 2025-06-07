<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class ProductoController extends Controller
{
    //crear un producto
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación de imagen
        ]);

        $producto = Producto::create($validate);

        if($request->hasFile('imagen')){
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagenPath;
        }

        $producto->save();

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'producto' => $producto,
        ], 201);
    }

    //obtener todos los productos
    

    //actualizar un producto
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación de imagen
        ]);

        $producto = Producto::findOrFail($id);

        Log::info('Datos recibidos para actualizar:', $validate);

        // Actualiza los datos de texto
        $producto->update($validate);

        // Si se envió una imagen, se procesa
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagenPath;
            $producto->save();
            Log::info('Imagen actualizada:', ['ruta' => $imagenPath]);
        }

        Log::info('Producto actualizado:', $producto->toArray());

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'producto' => $producto,
        ], 200);
    }

    public function index()
    {
        $productos = Producto::all();
        return response()->json([
            'productos' => $productos,
        ], 200);
    }

    //eliminar un producto(eliminado )
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado exitosamente',
        ], 200);
    }
}
