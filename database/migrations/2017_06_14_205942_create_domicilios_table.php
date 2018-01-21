<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomiciliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domicilios', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo_domicilio', ['Real', 'Social']);
            $table->string('calle', 50);
            $table->integer('numero')->unsigned();
            $table->integer('piso')->unsigned();
            $table->integer('ubicacion_id')->unsigned();
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
        Schema::dropIfExists('domicilios');
    }
}
