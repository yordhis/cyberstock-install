<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Po extends Model
{
    use HasFactory;

    protected $fillable = [
        "empresa",
        "rif",
        "direccion",
        "postal", // codigo postal
        "imagen", // logo
        "estatusImagen", // MOSTRAR O NO logo
    ];
}
