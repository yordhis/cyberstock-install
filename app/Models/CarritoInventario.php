<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoInventario extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo", // Codigo del movimiento
        "codigo_factura",
        "codigo_producto",
        "identificacion",
        "descripcion",
        "cantidad",
        "costo",
        "pvp",
        "pvp_2",
        "pvp_3",
        "fecha",
        "subtotal",
    ];
}
