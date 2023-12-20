<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 55)->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('cantidad', 255)->nullable();
            $table->string('almacen', 255)->nullable();
            $table->string('concepto_notificacion', 255)->default('El Stock del producto esta por terminarse!');
            $table->boolean('estatus', 11)->default(false); // vista true | por ver false
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
        Schema::dropIfExists('notificaciones');
    }
}
