<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = ["N/A"];

        foreach ($categorias as $key => $value) {
            $newCategoria = new Categoria();
            $newCategoria->nombre = $value;
            $newCategoria->save();
        }
    }
}
