<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'precio',
    ];

    // Relación con inventario diario
    public function inventarioDiario()
    {
        return $this->hasMany(InventarioDiario::class, 'producto_id');
    }

    // Relación con detalle ventas
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id');
    }
}