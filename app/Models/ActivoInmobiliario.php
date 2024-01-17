<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoInmobiliario extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo', 
        'descripcion', 
        'ubicacion',
        'fecha_compra',
        'cantidad',
        'costo',
        'estatus'
    ];
}
