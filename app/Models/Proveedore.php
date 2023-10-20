<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    use HasFactory;

    protected $fillable = [
        "tipo_documento",
        "codigo",
        "empresa",
        "rubro",
        "contacto", // nombre
        "telefono",
        "direccion",
        "correo",
        "edad",
        "nacimiento",
        "imagen",
        "estatus"
    ];
}
