<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClienteExportar implements FromCollection, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $clientes_array = [];

        $clientes = Cliente::all();

        foreach ($clientes as $key => $cliente) {
           array_push($clientes_array,[
                'codigo' => $cliente->id, 
                'nombre' => $cliente->nombre, 
                'cedula' => $cliente->tipo."-".$cliente->identificacion, 
                'telefono'  => $cliente->telefono, 
                'direccion' => $cliente->direccion, 
                'correo' => $cliente->correo
           ]);
        }


        return new Collection([
            ['CODIGO', 'NOMBRE', 'CEDULA', 'TELEFONO', 'DIRECCION', 'CORREO'],
            $clientes_array
        ]);
    }
}
