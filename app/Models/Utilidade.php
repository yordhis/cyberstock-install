<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilidade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "pvp_1", 
        "pvp_2", 
        "pvp_3", 
        "iva",
        "tasa"
    ];
}
