<?php

namespace App\Imports;

use App\Models\Cliente;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ClienteImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
           
            return new Cliente([
                "nombre" => strtoupper($row[0]),
                "identificacion" => $row[1],
                "telefono" => $row[2], 
                "direccion" => $row[3], 
                "correo" => $row[4], 
                "tipo" => $row[5] 
            ]);
    
    }
}
