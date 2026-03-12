<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'tipo_pago',
        'total'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    /*
    Relación con cliente
    */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /*
    Relación con detalle de venta
    */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /*
    Relación con pagos de crédito
    */
    public function pagosCredito()
    {
        return $this->hasMany(PagoCredito::class);
    }
    
}