<?php

namespace Database\Seeders;

use App\Models\Utilidade;
use Illuminate\Database\Seeder;

class UtilidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $utilidad = new Utilidade();
        $utilidad->save();
    }
}
