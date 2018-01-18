<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [ 'id' => 1, 'nombre' => 'Administrador'],
            [ 'id' => 2, 'nombre' => 'Supervisor'],
            [ 'id' => 3, 'nombre' => 'Operador'],
            [ 'id' => 4, 'nombre' => 'Proveedor'],
            [ 'id' => 5, 'nombre' => 'Usuario']
        ]);
    }
}
