<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_user_id')->unsigned(); 
            $table->integer('to_user_id')->unsigned();
            $table->integer('ancestro_id')->unsigned()->nullable();
            $table->integer('reserva_id')->unsigned();
            $table->string('mensaje', 400);
            $table->integer('estado')->default(1);
            $table->boolean('lectura')->default(false);
            $table->timestamps();

            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('ancestro_id')->references('id')->on('mensajes');
            $table->foreign('reserva_id')->references('id')->on('reservas');

        });
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
}
