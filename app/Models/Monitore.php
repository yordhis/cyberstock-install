<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitore extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario',  // email
        'nombre', 
        'transaccion', 
        'observacion',
        'codigo',
        'ubicacion', 
        'dispositivo', 
        'fecha'
    ];
}
