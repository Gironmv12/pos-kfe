<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\DB;


class ReporteController extends Controller
{
    public function productosVendidosEnPeriodo(Request $request)
    {
        $inicio = $request->input('inicio');
        $fin = $request->input('fin');

        $query = DetalleVenta::query()
            ->whereHas('venta', function($q) use ($inicio, $fin) {
                if ($inicio && $fin) {
                    $q->whereBetween('fecha_venta', [$inicio, $fin]);
                } elseif ($inicio) {
                    $q->where('fecha_venta', '>=', $inicio);
                } elseif ($fin) {
                    $q->where('fecha_venta', '<=', $fin);
                }
            })
            ->whereHas('producto', function($p) {
                $p->whereNull('deleted_at'); 
            })
            ->with(['producto' => function($p){
                $p->whereNull('deleted_at');
            }])
            ->selectRaw('producto_id, SUM(cantidad) as total_vendido')
            ->groupBy('producto_id');

        $ventas = $query->get();

        $productos = $ventas->map(function($venta) {
            return [
                'producto_id' => $venta->producto_id,
                'producto' => $venta->producto->nombre ?? 'Producto no encontrado',
                'total_vendido' => $venta->total_vendido,
            ];
        });

        return response()->json($productos);
    }

    //top 3 productos más vendidos
    public function top3ProductosMasVendidos()
    {
        $productos = DetalleVenta::with(['producto' => function($p){
                $p->whereNull('deleted_at');
            }])
            ->select('producto_id', DB::raw('SUM(cantidad) as total_vendida'))
            ->whereHas('producto', function($p){
                $p->whereNull('deleted_at');
            })
            ->groupBy('producto_id')
            ->orderByDesc('total_vendida')
            ->take(3)
            ->get()
            ->map(function($detalle) {
                return [
                    'producto_id' => $detalle->producto_id,
                    'producto' => $detalle->producto->nombre ?? 'Producto no encontrado',
                    'total_vendida' => $detalle->total_vendida,
                ];
            });

        return response()->json($productos);
    }

    //grafica de ventas por producto
    public function graficaVentasPorProductos()
    {
        $ventas = DetalleVenta::with('producto')
            ->select('producto_id', DB::raw('SUM(cantidad) as total_vendida'))
            ->whereHas('producto', function($query) {
                $query->whereNull('deleted_at');
            })
            ->groupBy('producto_id')
            ->orderByDesc('total_vendida')
            ->get()
            ->map(function ($detalle) {
                return [
                    'producto_id' => $detalle->producto_id,
                    'producto' => $detalle->producto->nombre ?? 'Producto no encontrado',
                    'total_vendida' => $detalle->total_vendida,
                ];
            });

        return response()->json($ventas);
    }
}
