<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo",
        "razon_social", // nombre de cliente o proveedor
        "identificacion", // numero de documento
        "subtotal", // se guarda en divisas
        "total",
        "tasa", // tasa en el momento que se hizo la transaccion
        "iva", // impuesto
        "tipo", // fiscal o no fialcal
        "concepto", // venta, compra ...
        "vendedor", // venta, compra ...
        "descuento", // descuento
        "fecha", // fecha venta, compra ...
        "metodos" // metodo de pago...
    ];
}
