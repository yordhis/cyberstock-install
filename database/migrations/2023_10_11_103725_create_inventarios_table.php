<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string("codigo", 255)->unique();
            $table->string("descripcion", 255)->nullable();
            $table->string("id_marca", 11)->nullable();
            $table->string("id_categoria", 11)->nullable();
            $table->string("cantidad", 11)->nullable();
            $table->string("costo", 11)->nullable();
            $table->string("pvp", 11)->nullable();
            // $table->string("ultimo_costo", 11)->nullable();
            // $table->string("cantidad_ultimo_costo", 11)->nullable();
            // $table->string("utilidad_personalizada", 55)->nullable();
            $table->string("imagen", 255)->default(FOTO_PORDEFECTO_PRODUCTO);
            $table->string("fecha_entrada", 85)->nullable();
            // $table->string("fecha_vencimiento", 85)->nullable();
            $table->string("estatus", 11)->default(1); // activo
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
        Schema::dropIfExists('inventarios');
    }
}