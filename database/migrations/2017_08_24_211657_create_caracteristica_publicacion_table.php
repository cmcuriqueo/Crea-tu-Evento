<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaracteristicaPublicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('caracteristica_publicacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('publicacion_id')->unsigned();
            $table->integer('caracteristica_id')->unsigned();
            $table->string('informacion')->nullable();

            $table->foreign('publicacion_id')->references('id')->on('publicaciones');
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
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
        Schema::dropIfExists('caracteristica_publicacion');
    }
}
