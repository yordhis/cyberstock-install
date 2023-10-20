<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

class InventarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
  

    public function definition()
    {

        return [
           
            // 'content' => $this->faker->paragraph,
            "codigo" => random_int(1,1000),
            "descripcion" =>  $this->faker->name('MOTO'),
            "id_marca" => random_int(1,7), 
            "id_categoria" => random_int(1,7), 
            "cantidad" => 55, 
            "costo" => 100, 
            "ultimo_costo" => 100, 
            "cantidad_ultimo_costo" => 100, 
            "utilidad_personalizada" => 35,
            "image" => "producto.png", 
            "fecha_entrada" => now(), 
            "fecha_vencimiento" => now(), 
            "estatus" => 1
        ];
    }
}
