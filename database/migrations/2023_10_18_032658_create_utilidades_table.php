<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilidades', function (Blueprint $table) {
            $table->id();
            $table->double("pvp_1", 55)->default(30);
            $table->double("pvp_2", 55)->default(20);
            $table->double("pvp_3", 55)->default(10);
            $table->double("iva", 55)->default(16);
            $table->double("tasa", 55)->default(35);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilidades');
    }
}
