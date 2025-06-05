<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    protected $table = 'productos';
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
    ];

    public function detalles(){
        return $this->hasMany(DetalleVenta::class, 'producto_id'); 
    }
}
