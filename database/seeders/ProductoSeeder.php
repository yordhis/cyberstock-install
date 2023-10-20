<?php

namespace Database\Seeders;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = [
            [
                "codigo" => "0001",
                "descripcion" => "Producto 1", 
                "costo" => 10, 
                "utilidad_personalizada" => null, 
                "imagen" => FOTO_PORDEFECTO_PRODUCTO, 
                "cantidad_inicial" => 100,
                "id_marca" => 3, 
                "id_categoria" => 2,
                "fecha_vencimiento" => now(),
                "ultimo_costo" => 10, 
                "cantidad_ultimo_costo" => 100, 
                "fecha_entrada" => now(), 
                
            ],
            [
                "codigo" => "0002",
                "descripcion" => "Producto 2", 
                "costo" => 1.99, 
                "utilidad_personalizada" => null, 
                "imagen" => FOTO_PORDEFECTO_PRODUCTO, 
                "cantidad_inicial" => 150,
                "id_marca" => 2, 
                "id_categoria" => 2,
                "fecha_vencimiento" => now(),
                "ultimo_costo" => 1.99, 
                "cantidad_ultimo_costo" => 150, 
                "fecha_entrada" => now(), 
            ],
            [
                "codigo" => "0003",
                "descripcion" => "Producto 3", 
                "costo" => 15, 
                "utilidad_personalizada" => null, 
                "imagen" => FOTO_PORDEFECTO_PRODUCTO, 
                "cantidad_inicial" => 50,
                "id_marca" => 4, 
                "id_categoria" => 6,
                "fecha_vencimiento" => now(),
                "ultimo_costo" => 15, 
                "cantidad_ultimo_costo" => 50, 
                "fecha_entrada" => now(), 
            ]
        ];


        foreach ($productos as $key => $producto) {
            $nuevoProducto = new Producto();
            $nuevoProducto->codigo = $producto['codigo'];
            $nuevoProducto->descripcion = $producto['descripcion'];
            $nuevoProducto->costo = $producto['costo'];
            $nuevoProducto->utilidad_personalizada = $producto['utilidad_personalizada'];
            $nuevoProducto->imagen = $producto['imagen'];
            $nuevoProducto->cantidad_inicial = $producto['cantidad_inicial'];
            $nuevoProducto->id_marca = $producto['id_marca'];
            $nuevoProducto->id_categoria = $producto['id_categoria'];
            $nuevoProducto->fecha_vencimiento = $producto['fecha_vencimiento'];
            $nuevoProducto->save();

            // Procesar entrada
            $entradaInventario = new Inventario();
            $entradaInventario->codigo = $producto['codigo'];
            $entradaInventario->descripcion = $producto['descripcion'];
            $entradaInventario->costo = $producto['costo'];
            $entradaInventario->utilidad_personalizada = $producto['utilidad_personalizada'];
            $entradaInventario->imagen = $producto['imagen'];
            $entradaInventario->cantidad = $producto['cantidad_inicial'];
            $entradaInventario->cantidad_ultimo_costo = $producto['cantidad_ultimo_costo'];
            $entradaInventario->ultimo_costo = $producto['ultimo_costo'];
            $entradaInventario->id_marca = $producto['id_marca'];
            $entradaInventario->id_categoria = $producto['id_categoria'];
            $entradaInventario->fecha_vencimiento = $producto['fecha_vencimiento'];
            $entradaInventario->fecha_entrada = $producto['fecha_entrada'];
            $entradaInventario->save();
        }
    }
}
