<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioDiario extends Model
{
    use HasFactory;

    protected $table = 'inventario_diario';

    protected $fillable = [
        'producto_id',
        'fecha',
        'cantidad_inicial',
        'cantidad_disponible',
    ];

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Accesor para mostrar fecha en formato legible
    public function getFechaFormateadaAttribute()
    {
        return \Carbon\Carbon::parse($this->fecha)->format('d/m/Y');
    }
}