<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $fillable  = [
        "codigo",
        "descripcion",
        "id_marca", 
        "id_categoria", 
        "cantidad", 
        "costo", 
        "pvp", 
        "imagen", 
        "fecha_entrada", 
        "estatus"
        // "fecha_vencimiento", 
        // "ultimo_costo", 
        // "cantidad_ultimo_costo", 
        // "utilidad_personalizada",
    ];
}
