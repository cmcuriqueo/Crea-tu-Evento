<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('formatted_address');
            $table->string('locality');
            $table->string('administrative_area_level_2');
            $table->string('administrative_area_level_1');
            $table->string('country');
            $table->string('name');
            $table->double('lat', 12, 8);
            $table->double('lng', 12, 8);
            $table->string('place_id');
            $table->string('api_id');
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
        Schema::dropIfExists('ubicaciones');
    }
}
