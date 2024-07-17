<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        
        "codigo", // Codigo de transaccion
        "codigo_factura",
        "razon_social", // proveedor
        "identificacion", // rif o cedula
        "subtotal", // se guarda en divisas
        "total",
        "tasa", // tasa en el momento que se hizo la transaccion
        "iva", // impuesto fiscal o no fialcal
        "tipo", // 1 = entrada y 2 = salida
        "concepto", // venta, compra, devolucion, consumo...
        "observacion", // decribe motivo de la salida o entrada
        "descuento", // descuento 10%...
        "metodos", // forma de pago
        "fecha" // fecha..
        
    ];
}
