<?php

namespace Database\Seeders;

use App\Models\RolPermiso;
use Illuminate\Database\Seeder;

class RolPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            "root" => 1,
            "administrador" => 2,
            "vendedor" => 3
        ];

        $permisosDeRoot = [
            "panel" => 1,
            "inventarios" => 2,
            "pos" => 3,
            "proveedores" => 4,
            "configuraciones" => 5,
        ];
        $permisosDeAdministrador = [
            "panel" => 1,
            "inventarios" => 2,
            "pos" => 3,
            "proveedores" => 4,
            "configuraciones" => 5,
            "productos" => 6,
        ];

        $permisosDeVendedor = [
            "panel" => 1,
            "pos" => 2
        ];

        foreach ($permisosDeRoot as $key => $value) {
            $permiso = new RolPermiso();
            $permiso->id_rol = $roles['root'];
            $permiso->id_permiso = $value;
            $permiso->save();
        }
        foreach ($permisosDeAdministrador as $key => $value) {
            $permiso = new RolPermiso();
            $permiso->id_rol = $roles['administrador'];
            $permiso->id_permiso = $value;
            $permiso->save();
        }

        foreach ($permisosDeVendedor as $key => $value) {
            $permiso = new RolPermiso();
            $permiso->id_rol = $roles['vendedor'];
            $permiso->id_permiso = $value;
            $permiso->save();
        }
    }
}
