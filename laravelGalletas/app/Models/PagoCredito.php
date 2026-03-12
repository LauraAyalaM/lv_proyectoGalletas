<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCredito extends Model
{
    use HasFactory;

    protected $table = 'pagos_credito';

    protected $fillable = [
        'venta_id',
        'monto',
        'fecha',
        'metodo_pago',
    ];

    // Relación con la venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Accesor para mostrar fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return \Carbon\Carbon::parse($this->fecha)->format('d/m/Y H:i');
    }
}