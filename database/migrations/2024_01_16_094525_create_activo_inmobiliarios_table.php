<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivoInmobiliariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activo_inmobiliarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 55)->unique();
            $table->string('descripcion', 255);
            $table->string('ubicacion',255);
            $table->string('fecha_compra',155);
            $table->double('cantidad',11);
            $table->double('costo',11);
            $table->boolean('estatus',11)->default(1);
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
        Schema::dropIfExists('activo_inmobiliarios');
    }
}
