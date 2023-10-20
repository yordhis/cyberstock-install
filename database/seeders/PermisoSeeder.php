<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permisos = [
            "panel",
            "inventarios",
            "pos",
            "proveedores",
            "configuraciones",
            "productos",
        ];

        foreach ($permisos as $key => $value) {
            $permiso = new Permiso();
            $permiso->nombre = $value;
            $permiso->save();
        }

    }
}
