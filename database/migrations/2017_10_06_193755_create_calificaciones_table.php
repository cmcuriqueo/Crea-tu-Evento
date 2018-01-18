<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('publicacion_id')->unsigned();
            $table->integer('reserva_id')->unsigned();
            $table->integer('calidad');
            $table->integer('calidad_precio');
            $table->integer('profesionalidad');
            $table->integer('respuesta');
            $table->boolean('recomendar')->nullbale();
            $table->string('comentario', 300 );
            $table->float('puntuacion_total', 5, 2);

            $table->foreign('publicacion_id')->references('id')->on('publicaciones');
            $table->foreign('reserva_id')->references('id')->on('reservas');
         
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
        Schema::dropIfExists('calificaciones');
    }
}
