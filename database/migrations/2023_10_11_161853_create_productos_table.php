<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("codigo", 155)->unique();
            $table->string("descripcion", 255)->nullable();
            // $table->string("costo", 55)->nullable();
            // $table->float("utilidad_personalizada", 13)->default(0);
            // $table->string("cantidad_inicial", 11)->nullable();
            $table->string("imagen", 255)->default(FOTO_PORDEFECTO_PRODUCTO);
            $table->string("id_marca", 11)->nullable();
            $table->string("id_categoria", 11)->nullable();
            $table->string("fecha_vencimiento", 155)->nullable();
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
        Schema::dropIfExists('productos');
    }
}
