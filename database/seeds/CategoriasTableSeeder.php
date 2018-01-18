<?php

use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          DB::table('categorias')->insert([
            [ 'id' => 1, 'nombre' => 'Musica, Arte', 'tipo_proveedor' => 'Servicio'],
            [ 'id' => 2, 'nombre' => 'Belleza y  Cuidado Personal', 'tipo_proveedor' => 'Servicio'],
            [ 'id' => 3, 'nombre' => 'Alquiler', 'tipo_proveedor' => 'Servicio'],
            [ 'id' => 4, 'nombre' => 'Fiesta', 'tipo_proveedor' => 'Servicio'],
            [ 'id' => 5, 'nombre' => 'Imprenta', 'tipo_proveedor' => 'Servicio'],
            [ 'id' => 6, 'nombre' => 'Comida y Bebida', 'tipo_proveedor' => 'Servicio'],
            [ 'id' => 7, 'nombre' => 'Imprenta', 'tipo_proveedor' => 'Servicio']
        ]);
    }
}
