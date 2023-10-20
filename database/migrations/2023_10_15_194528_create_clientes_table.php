<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 255)->nullable();
            $table->string("identificacion", 55)->nullable();
            $table->string("telefono", 155)->nullable();
            $table->text("direccion")->nullable();
            $table->string("correo", 255)->nullable();
            $table->string("tipo", 55)->default('V'); // contribuyento o no
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
        Schema::dropIfExists('clientes');
    }
}
