<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\{
    // UsuarioSeeder,
    UserSeeder,
    ProfesoreSeeder,
    EstudianteSeeder,
    DificultadeSeeder,
    NiveleSeeder,
    ConceptoSeeder
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermisoSeeder::class);
        $this->call(RolPermisoSeeder::class);
        // $this->call(InventarioSeeder::class);
        $this->call(UtilidadeSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(PoSeeder::class);
        
    }
}
