<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo",
        "descripcion", 
        "imagen", 
        "id_marca", 
        "id_categoria",
        "fecha_vencimiento"
        // "costo", 
        // "utilidad_personalizada", 
        // "cantidad_inicial",
    ];
}
