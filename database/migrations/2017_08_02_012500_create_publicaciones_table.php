<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('publicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_finalizacion')->nullable();
            $table->string('titulo');
            $table->string('oferta')->nullable();
            $table->string('descripcion', 30000);
            $table->integer('estado')->default(1);
            $table->integer('rubros_detalle_id')->unsigned();
            $table->foreign('rubros_detalle_id')->references('id')->on('rubros_detalle');

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
        Schema::dropIfExists('publicaciones');
    }
}
