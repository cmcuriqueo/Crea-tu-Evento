<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion', 60);
            $table->integer('by_user_id')->unsigned()->nullable();
            $table->integer('log_id')->unsigned()->nullable();
            $table->integer('for_role_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('by_user_id')->references('id')->on('users');
            $table->foreign('log_id')->references('id')->on('logs');
            $table->foreign('for_role_id')->references('id')->on('roles');
        });
        Schema::create('notificaciones_vistas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notificacion_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('notificacion_id')->references('id')->on('notificaciones');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificaciones_vistas');
        Schema::dropIfExists('notificaciones');
    }
}
