<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\User;
use App\Proveedor;
class ProveedorTest extends TestCase
{


    public function testShowProveedor()
    {
    	$this->withoutMiddleware();
    	$proveedor = Proveedor::aprobado()->first();
        // Assert that each user in the list has at least an id, name and email attribute.
        $this->get('api/proveedor/'.$proveedor->id)
             ->seeJsonStructure([
				'data' => [
					'id', 'cuit', 'nombre', 'email', 'ingresos_brutos', 'user_id', 'domicilio_id', 'estado', 'created_at', 'updated_at',
					'adjunto', 'observaciones', 'register_by_user_id', 'accepted_by_user_id', 'rejected_by_user_id', 'telefono_id',
					'domicilio' => [
						'id', 'tipo_domicilio', 'calle', 'numero', 'piso', 'localidad_id', 'created_at', 'updated_at',
						'localidad' => [
							'id', 'provincia_id', 'nombre', 'created_at', 'updated_at',
							'provincia' => [
								'id', 'nombre', 'created_at', 'updated_at'
							]
						]
					],
					'user' => [
						'id','name', 'email', 'created_at', 'updated_at', 'estado', 'roles_id',
						'usuario' => [
							'id','avatar','nombre', 'apellido', 'fecha_nac', 'sexo', 'user_id', 'localidad_id', 'created_at', 'updated_at'
						]
					],
					'telefono' => [
						'id', 'cod_area', 'numero', 'created_at', 'updated_at'
					]
				]
             ]);
    }

    public function testPostPublicacion(){
		$proveedor = Proveedor::aprobado()->first();
		Auth::loginUsingId($proveedor->user_id);
        $stub = __DIR__.'/stubs/test.png';
        $name = str_random(8).'.png';
        $path = sys_get_temp_dir().'/'.$name;

        copy($stub, $path);

        $file = new UploadedFile($path, $name, filesize($path), 'image/png', null, true);
		$publicacion = [	
			'articulos' => [7],
			'calle' => null,
			'caracteristicas'=> [],
			'comercio' => false,
			'descripcion' => "<p>Este es el cuerpo de la publicacion hehehehe</p>",
			'fecha_finalizacion' => null,
			'fecha_habilitacion' => null,
			'fotos' =>	array($file),
			'habilitacion' => null,
			'horariosId' => [],
			'localidad_id' =>null,
			'numero' => null,
			'oferta'=> "",
			'piso' => null,
			'precio' => 30,
			'rubros_id' => [4],
			'subcategoria_id' => 16,
			'titulo' =>"Este es el Titulo"
        ];
		$this->json('POST', 'api/publicacion', $publicacion)->assertResponseStatus(200);

		@unlink($uploaded);
    }

    
}
