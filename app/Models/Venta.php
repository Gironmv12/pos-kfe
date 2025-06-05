<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'usuario_id',
        'total',
        'fecha_venta',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function detalles()
    {
    return $this->hasMany(DetalleVenta::class,'venta_id');
    }
}
