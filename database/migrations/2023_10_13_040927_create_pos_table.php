<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->string("empresa")->nullable();
            $table->string("rif")->nullable();
            $table->string("direccion")->nullable();
            $table->string("postal")->default(5201); // codigo postal
            $table->string("imagen")->default(LOGO_PORDEFECTO); // logo
            $table->string("estatusImagen")->default(0); // logo
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
        Schema::dropIfExists('pos');
    }
}
