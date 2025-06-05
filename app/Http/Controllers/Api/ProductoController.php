<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

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
        ]);

        $producto = Producto::create($validate);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'producto' => $producto,
        ], 201);
    }

    //obtener todos los productos
    public function index()
    {
        $productos = Producto::all();
        return response()->json([
            'productos' => $productos,
        ], 200);
    }

    //actualizar un producto
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($validate);

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'producto' => $producto,
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
