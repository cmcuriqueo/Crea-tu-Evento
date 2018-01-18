<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cuit')->length(12)->unsigned(); 
            $table->string('nombre', 50);  //nombre o razon social o denominacion
            $table->string('email', 50);
            $table->integer('ingresos_brutos')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('domicilio_id')->unsigned();//real
            $table->enum('estado', ['Tramite', 'Aprobado', 'Rechazado','Baja']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('domicilio_id')->references('id')->on('domicilios');
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
        Schema::dropIfExists('proveedores');
    }
}
