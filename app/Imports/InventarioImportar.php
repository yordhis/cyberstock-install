<?php

namespace App\Imports;

use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Marca;
use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;

class InventarioImportar implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $marcaExiste = Marca::select('id')->where('nombre', $row[2])->get();
        $categoriaExiste = Categoria::select('id')->where('nombre', $row[3])->get();
        $productoExiste = Producto::select('id')->where('codigo', $row[0])->get();

        if(count($marcaExiste)){
            $row[2] = $marcaExiste[0]->id;
        }else{
            $marca = Marca::create([
                "nombre" => $row[2]
            ]);
            $row[2] = $marca->id;
        }

        if(count($categoriaExiste)){
            $row[3] = $categoriaExiste[0]->id;
        }else{
            $categoria = Categoria::create([
                "nombre" => $row[3]
            ]);
            $row[3] = $categoria->id;
        }

        if(!count($productoExiste)){
            Producto::create([
                "codigo"        => $row[0],
                "descripcion"   => $row[1], 
                "id_marca"      => $row[2], 
                "id_categoria"  => $row[3],
            ]);
        }

        return new Inventario([
            "codigo" => $row[0],
            "descripcion" => $row[1],
            "id_marca" => $row[2], 
            "id_categoria" => $row[3], 
            "cantidad" => $row[4], 
            "costo" => number_format(floatval( $row[5] ), 2), 
            "pvp" => number_format(floatval( $row[6] ), 2),
            "pvp_2" => number_format(floatval( $row[7] ), 2), 
            "pvp_3" =>number_format(floatval( $row[8] ), 2),  
            "fecha_entrada" => $row[9]
        ]);
    }
}
