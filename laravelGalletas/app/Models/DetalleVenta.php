<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Relación con la venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Calcular subtotal automáticamente
    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = $this->cantidad * $this->precio_unitario;
    }
}