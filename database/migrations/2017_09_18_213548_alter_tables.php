<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('publicaciones', function (Blueprint $table) {
            $table->integer('descuento')->nullable();
            $table->dropForeign(['rubros_detalle_id']);
            $table->dropColumn('rubros_detalle_id');
            $table->integer('proveedor_id')->unsigned();
            $table->integer('subcategoria_id')->unsigned();

           // $table->foreign('proveedor_id')->references('id')->on('proveedores');
            //$table->foreign('subcategoria_id')->references('id')->on('subcategorias');

            $table->double('precio', 15,2);
        });

        Schema::table('rubros', function (Blueprint $table) {
            $table->dropForeign(['subcategoria_id']);
            $table->dropColumn('subcategoria_id');

            $table->boolean('salon')->default(false);
            $table->boolean('servicio')->default(false);
            $table->boolean('producto')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
