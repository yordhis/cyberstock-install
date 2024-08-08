<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendedorToFacturaInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factura_inventarios', function (Blueprint $table) {
            $table->string('vendedor', 255)->nullable()->after('observacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factura_inventarios', function (Blueprint $table) {
            $table->dropColumn('vendedor');
        });
    }
}
