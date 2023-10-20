<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->nullable(); // Codigo de la factura
            $table->string("codigo_producto")->nullable();
            $table->string("identificacion")->nullable();
            $table->string("descripcion")->nullable();
            $table->string("cantidad")->nullable();
            $table->string("costo")->nullable();
            $table->string("subtotal")->nullable();
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
        Schema::dropIfExists('carritos');
    }
}
