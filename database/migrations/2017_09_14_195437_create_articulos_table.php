<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proveedor_id')->unsigned();
            $table->integer('rubro_id')->unsigned();
            $table->string('nombre');//detalle
            $table->double('precio', 15,2)->nullable();
            $table->integer('stock');
            $table->integer('estado');

            $table->foreign('rubro_id')->references('id')->on('rubros');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->timestamps();
        });

        Schema::create('articulo_publicacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('articulo_id')->unsigned();
            $table->integer('publicacion_id')->unsigned();

            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('publicacion_id')->references('id')->on('publicaciones');

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
        Schema::dropIfExists('articulos');
    }
}
