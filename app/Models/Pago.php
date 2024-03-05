<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $fillable = [
      'codigo_factura',
      'tipo_factura',
      'metodo', 
      'monto',
      'fecha',
    ];
}
