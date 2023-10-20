<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "nombre" => $this->faker->firstName(),
            "identificacion" => random_int(20000000,40000000),
            "telefono" => $this->faker->phoneNumber(),
            "direccion" => $this->faker->address(),
            "correo" => $this->faker->email(),
            "tipo" => "V",
        ];
    }
}
