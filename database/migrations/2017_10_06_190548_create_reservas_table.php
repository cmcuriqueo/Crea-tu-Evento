<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('publicacion_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('domicilio_id')->unsigned()->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_finalizacion')->nullable();
            $table->enum('estado', ['presupuesto', 'reservado', 'confirmado', 'cancelado']);
            $table->boolean('presupuestado')->default(false);
            $table->double('precio_total', 15,2);
            $table->integer('horario_id')->unsigned()->nullable();

            $table->foreign('publicacion_id')->references('id')->on('publicaciones');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('domicilio_id')->references('id')->on('domicilios');
            $table->foreign('horario_id')->references('id')->on('horarios');
            $table->timestamps();
        });

        Schema::create('articulo_reserva', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reserva_id')->unsigned();
            $table->integer('articulo_id')->unsigned();
            $table->integer('cantidad')->nullable();

            $table->foreign('reserva_id')->references('id')->on('reservas');
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->timestamps();
        });

        Schema::create('reserva_rubro', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reserva_id')->unsigned();
            $table->integer('rubro_id')->unsigned();

            $table->foreign('reserva_id')->references('id')->on('reservas');
            $table->foreign('rubro_id')->references('id')->on('rubros');
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
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('articulo_reserva');
         Schema::dropIfExists('reserva_rubro');
    }
}
