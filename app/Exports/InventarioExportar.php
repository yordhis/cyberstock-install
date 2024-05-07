<?php

namespace App\Exports;

use App\Models\Inventario;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarioExportar implements FromCollection, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $inventario_array = [];

        $inventarios = Inventario::join('marcas', 'marcas.id', '=', 'inventarios.id_marca')
        ->join('categorias', 'categorias.id', '=', 'inventarios.id_categoria')
        ->select(
            'inventarios.*',
            'marcas.id',
            'marcas.nombre as marca',
            'categorias.id',
            'categorias.nombre as categoria',
        )->get();

        foreach ($inventarios as $key => $inventario) {
           array_push($inventario_array,[
                'codigo' => $inventario->codigo, 
                'descripcion' => $inventario->descripcion, 
                'marca' => $inventario->marca, 
                'cetegoria'  => $inventario->categoria, 
                'cantidad' => $inventario->cantidad, 
                'costo' => $inventario->costo, 
                'pvp' => $inventario->pvp, 
                'pvp_2' => $inventario->pvp_2, 
                'pvp_3' => $inventario->pvp_3
           ]);
        }


        return new Collection([
            ['CODIGO', 'DESCRIPCION', 'MARCA', 'CATEGORIA', 'CANTIDAD', 'COSTO', 'PVP', 'PVP_2', 'PVP_3'],
            $inventario_array
        ]);
    }
}
