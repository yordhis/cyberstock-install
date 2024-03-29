<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas = ["N/A"];

        foreach ($marcas as $key => $value) {
            $newMarca = new Marca();
            $newMarca->nombre = $value;
            $newMarca->save();
        }
    }
}
