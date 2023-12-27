<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->nombre = "ROOT";
        $user->rol = 1;
        $user->email = "@root";
        $user->password = Hash::make("Cs24823972//**");
        $user->save();

        $user = new User();
        $user->nombre = "ADMINISTRADOR";
        $user->rol = 2;
        $user->email = "@admin";
        $user->password = Hash::make(12345678);
        $user->save();

        $userDos = new User();
        $userDos->nombre = "VENDEDOR";
        $userDos->email = "@vendedor";
        $userDos->password = Hash::make(12345678);
        $userDos->save();
    }
}
