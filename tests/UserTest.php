<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use App\Localidad;
use App\Provincia;
//https://gist.github.com/JeffreyWay/5623836 ejemplos
//Auth::loginUsingId(1);
class UserTest extends TestCase
{
	use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRaiz()
    {
	    $response = $this->call('GET', '/');
	    $this->assertEquals(200, $response->status());    
	}

	public function testRegisterWithErrors(){
		$user = [	'name' => 'Tar',
	            'email' => 'asasas',
	            'password' => '123456',
	            'fecha_nac' => '1980/05/05'
            ];
		$this->json('POST', 'api/register', $user)->assertResponseStatus(422);
	}

	public function testRegister(){
		$localidad = Localidad::first();

		$user = [	
				'name' => 'Taylor',
	            'email' => 'asasas@gmail.com',
	            'password' => '123456',
	            'password_confirmation' => '123456',
	            'nombre' => 'jose',
	            'apellido' => 'lopez',
	            'sexo' => 'M',
	            'localidad_id' => $localidad->id,
	            'fecha_nac' => '1966-06-22',
	            'login' => false
            ];
		$this->json('POST', 'api/register', $user)->assertResponseStatus(200);
	}

	public function testLogin(){
		//$user = factory(App\User::class)->create();
		$this->json('POST', 'api/login', 
			[
	            'email' => 'test@test.com',
	            'password' => 'secret',
	            'remember' => false
            ])->assertResponseStatus(200);

	}

}
