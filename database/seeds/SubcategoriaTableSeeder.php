<?php

use Illuminate\Database\Seeder;

class SubcategoriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          DB::table('subcategorias')->insert([
            ['nombre' => 'Fotografia Creativa', 'categoria_id' => 1],
            ['nombre' => 'Musica', 'categoria_id' => 1],
            ['nombre' => 'Video Filmaciones', 'categoria_id' => 1],
            ['nombre' => 'Maquillaje y Peinado', 'categoria_id' => 2],
            ['nombre' => 'Peluqueria', 'categoria_id' => 2],
            ['nombre' => 'Manicura y Pedicura', 'categoria_id' => 2],
            ['nombre' => 'Equipamiento', 'categoria_id' => 3],
            ['nombre' => 'Indumentaria', 'categoria_id' => 3],
            ['nombre' => 'Salones', 'categoria_id' => 3],
            ['nombre' => 'Salones Infantiles', 'categoria_id' => 3],
            ['nombre' => 'Quintas p/ Eventos', 'categoria_id' => 3],
            ['nombre' => 'Vehiculos', 'categoria_id' => 3],
            ['nombre' => 'Juegos de Salones', 'categoria_id' => 3],
            ['nombre' => 'Inflables', 'categoria_id' => 3],
            ['nombre' => 'Entretenimientos', 'categoria_id' => 4],
            ['nombre' => 'Catering', 'categoria_id' => 4],
            ['nombre' => 'Bebidas', 'categoria_id' => 4]

        ]);
    }
}
