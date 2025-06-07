<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{
    public function store(Request $request){
        Log::info('Iniciando método store en VentaController', ['data_recibida' => $request->all()]);

        //validamos que reciba la informacion correctamente
        $validateData = $request->validate([
            'usuario_id'=> 'required|integer|exists:usuarios,id',
            'total'=> 'required|numeric|min:0',
            'fecha_venta'=> 'required|date',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|integer|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.subtotal' => 'required|numeric|min:0',
        ]);

        try{
            DB::beginTransaction();
            Log::info('Validación completada, iniciando verificación de stock');

            // Verificación de stock disponible
            foreach($validateData['detalles'] as $detalle){
                $producto = Producto::find($detalle['producto_id']);
                Log::info('Verificando producto', ['producto_id' => $detalle['producto_id'], 'stock' => $producto->stock ?? null]);

                if(!$producto || $producto->stock < $detalle['cantidad']){
                    throw new Exception('Stock insuficiente para el producto con ID: ' . $detalle['producto_id']);
                }
            }

            Log::info('Stock verificado, creando venta...');

            //creamos la venta(cabecera)
            $venta = Venta::create([
                'usuario_id'=> $validateData['usuario_id'],
                'total'=> $validateData['total'],
                'fecha_venta'=> $validateData['fecha_venta'],
            ]);

            Log::info('Venta creada', ['venta_id' => $venta->id]);


            //creamos los detalles para crearlos asociados a la venta
            foreach($validateData['detalles'] as $detalle){
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['subtotal'],
                ]);
                Log::info('Detalle de venta creado', [
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['subtotal'],
                ]);
            }
            //actualizamos el stock de los productos
            $producto = Producto::find($detalle['producto_id']);
            if ($producto) {
                $producto->stock -= $detalle['cantidad'];
                $producto->save();
            }
            DB::commit();
            Log::info('Transacción completada, venta registrada exitosamente', ['venta_id' => $venta->id]);

            return response()->json([
            'success' => true,
            'venta_id' => $venta->id,
            // 'pdf_url' => $pdfUrl, // incluir si se genera el PDF.
        ], 201);

        }catch (Exception $e) {
        DB::rollBack();
        Log::error('Error al registrar la venta', [
            'error' => $e->getMessage(),
            'venta_data' => $validateData,
        ]);
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
    }
}
