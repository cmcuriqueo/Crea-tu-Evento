<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('avatar', 200);
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->date('fecha_nac');
            $table->enum('sexo', ['F', 'M']);
            $table->integer('user_id')->unsigned();
            $table->integer('ubicacion_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones');
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
        Schema::dropIfExists('usuarios');
    }
}
