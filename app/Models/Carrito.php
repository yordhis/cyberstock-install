<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo", // Codigo de la factura
        "codigo_producto",
        "identificacion",
        "cantidad",
        "costo",
        "descripcion",
        "subtotal",
    ];
}
