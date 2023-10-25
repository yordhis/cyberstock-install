<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritoInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrito_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->nullable(); // Codigo de la transaccion
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
        Schema::dropIfExists('carrito_inventarios');
    }
}
