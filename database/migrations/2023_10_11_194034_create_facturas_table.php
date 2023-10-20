<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->nullable();
            $table->string("razon_social")->nullable(); // nombre de cliente o proveedor
            $table->string("identificacion")->nullable(); // numero de documento
            $table->double("subtotal")->nullable(); // se guarda en divisas
            $table->double("total")->nullable();
            $table->double("tasa")->nullable(); // tasa en el momento que se hizo la transaccion
            $table->double("iva")->nullable(); // impuesto
            $table->string("tipo")->nullable(); // fiscal o no fialcal
            $table->string("concepto")->nullable(); // venta, compra ...
            $table->string("descuento")->nullable(); // descuento 10%...
            $table->string("metodos")->nullable(); // descuento 10%...
            $table->string("fecha")->nullable(); // descuento 10%...

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
        Schema::dropIfExists('facturas');
    }
}
