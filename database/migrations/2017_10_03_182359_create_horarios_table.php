<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('horarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('publicacion_id')->unsigned()->nullable();
            $table->integer('proveedor_id')->unsigned();
            $table->time('hora_inicio')->default('00:00:00');
            $table->time('hora_fin')->default('00:00:00');
            $table->enum('dia', ['lunes','martes','miercoles','jueves','viernes','sabado','domingo']);
            $table->double('precio', 15,2)->nullable();

            $table->foreign('publicacion_id')->references('id')->on('publicaciones');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
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
        Schema::dropIfExists('horarios');
    }
}
