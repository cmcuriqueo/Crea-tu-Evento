<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Reserva;
use App\Publicacion;
use App\Horario;
class ReservaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHorariosDisponibles()
    {
    	$publicacion = Publicacion::where('id', 8)->first();

        $this->get('api/publicacion/'.$publicacion->id.'/horario/2017-12-22')
             ->seeJsonStructure([
             	'*' => [
					'id', 'publicacion_id', 'hora_inicio', 'hora_fin', 'dia', 'precio', 
					'created_at', 'updated_at', 'proveedor_id', 'estado'
				]
    		]);
    }

    public function testDisponibles()
    {
    	$publicacion = Publicacion::where('id', 14)->first();

        $this->get('api/publicacion/'.$publicacion->id.'/horario/2017-12-22')
             ->seeJson([
             	'estado' => 'disponible'
    		]);
    }

    public function testNoDisponibles()
    {
    	$publicacion = Publicacion::where('id', 14)->first();

        $this->get('api/publicacion/'.$publicacion->id.'/horario/2017-12-15')
             ->seeJson([
             	'estado' => 'no disponible'
    		]);
    }

    public function testPresupuesto(){
        $user = User::where('roles_id', 5)->firts();
        Auth::loginUsingId($user->id);
        $publicacion = Publicacion::where('id', 8)->first();
        $data = [
            'articulos' => [ {'articulo_id' => 7, 'cantidad' => 0}, {'articulo_id' => 8, 'cantidad' => 0} ],
            'calle' => null,
            'comentario' => "Necesito el salón para unas 50.",
            'estado' => "presupuesto",
            'fecha' => "2017-12-27T03:00:00.000Z",
            'horario_id' => 3,
            'numero' => null,
            'piso' => null,
            'rubros' => [1, 2, 3, 4]
        ]
        $this->json('POST', 'api/publicacion/'.$publicacion->id.'/presupuesto', $data)->assertResponseStatus(200);
    }

}
