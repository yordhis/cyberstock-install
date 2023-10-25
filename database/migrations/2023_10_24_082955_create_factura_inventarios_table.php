<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->nullable(); // Codigo de transaccion
            $table->string("codigo_factura")->nullable();
            $table->string("razon_social")->nullable(); // proveedor
            $table->string("identificacion")->nullable(); // rif o cedula
            $table->double("subtotal")->nullable(); // se guarda en divisas
            $table->double("total")->nullable();
            $table->double("tasa")->nullable(); // tasa en el momento que se hizo la transaccion
            $table->double("iva")->nullable(); // impuesto fiscal o no fialcal
            $table->string("tipo")->nullable(); // 1 = entrada y 2 = salida
            $table->string("concepto")->nullable(); // venta, compra, devolucion, consumo...
            $table->string("observacion")->nullable(); // decribe motivo de la salida o entrada
            $table->string("descuento")->nullable(); // descuento 10%...
            $table->string("metodos")->nullable(); // forma de pago
            $table->string("fecha")->nullable(); // fecha..
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
        Schema::dropIfExists('factura_inventarios');
    }
}
