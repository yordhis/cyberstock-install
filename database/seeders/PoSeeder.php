<?php

namespace Database\Seeders;

use App\Models\Po;
use Illuminate\Database\Seeder;

class PoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pos = new Po();
        $pos->empresa = "Nombre Empresa"; 
        $pos->rif = "J-0000000-0"; 
        $pos->direccion = "BARINAS - VENEZUELA"; 
        $pos->save(); 
    }
}
