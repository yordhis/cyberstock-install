<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $rolUno = new Role();
       $rolUno->nombre = "ROOT";
       $rolUno->save();
       
       $rolDos = new Role();
       $rolDos->nombre = "ADMINISTRADOR";
       $rolDos->save();

       $rolTres = new Role();
       $rolTres->nombre = "VENDEDOR";
       $rolTres->save();
    }
}
