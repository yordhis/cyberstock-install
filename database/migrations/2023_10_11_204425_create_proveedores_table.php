<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string("tipo_documento", 11)->default("V");
            $table->string("codigo", 155)->unique();
            $table->string("empresa")->nullable();
            $table->string("rubro")->nullable();
            $table->string("contacto")->nullable(); // nombre
            $table->string("telefono")->nullable();
            $table->string("direccion")->nullable();
            $table->string("correo")->nullable();
            $table->string("edad")->nullable();
            $table->string("nacimiento")->nullable();
            $table->string("imagen")->default(FOTO_PORDEFECTO);
            $table->string("estatus")->default(1);
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
        Schema::dropIfExists('proveedores');
    }
}
