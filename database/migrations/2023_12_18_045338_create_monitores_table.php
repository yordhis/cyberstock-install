<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitores', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 255); // email
            $table->string('nombre', 255);
            $table->string('transaccion', 255);
            $table->string('ubicacion', 255)->nullable();
            $table->string('dispositivo', 255)->nullable();
            $table->string('codigo', 255)->nullable();
            $table->string('observacion', 255)->nullable();
            $table->string('fecha', 255);
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
        Schema::dropIfExists('monitores');
    }
}
