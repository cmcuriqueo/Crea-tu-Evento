<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\UsuarioRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Usuario;
use App\Ubicacion;
use App\User;
use App\Role;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterUserController extends Controller
{
    protected function validatorUsers(Request $request)
    {
      return $this->validate($request, 
        [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
    }

    protected function validatorUsuario(Request $request)
    {
      return $this->validate($request, 
        [
            'nombre' => 'required|min:4|max:55',
            'apellido' => 'required|min:4|max:55',
            'sexo' => 'required|in:F,M',
            'localidad_id' => 'required',
            'fecha_nac' => 'required|date'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        $name = $request->name.random_int(9999, 9999999);
        $user = User::where('name', $name)->first();
        while($user){
          $name = $request->name.random_int(9999, 9999999);
          $user = User::where('name', $name)->first();
        }
        return User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => bcrypt($request->password)]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validatorUsers($request);
        $this->validatorUsuario($request);

        event(new Registered($user = $this->create($request)));
        $role = Role::findOrFail(5);
        $user->roles()->attach($role);
        
        if(!$request->has('login') || ($request->has('login') && $request->login)){
          $token = JWTAuth::fromUser($user);
        }
        $meta['token'] = $token;
        $ubicacion = $this->createUbicacion($request);
        $this->createUsuario($request, $user->id, $ubicacion);
        $user = User::where('id', $user->id)->with('usuario.ubicacion', 'roles')->first();
        return response()->json(['data' =>  $user, 'meta' => $meta, 'csrfToken' => csrf_token()], 200);
    }


    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    protected function createUbicacion(Request $request){
        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$request->localidad_id."&key=".env('GOOGLE_PLACES_KEY')."&language=es";

        $response = file_get_contents($url);
        $response = json_decode($response);
        $ubicacion = new Ubicacion;
        $ubicacion->formatted_address = $response->result->formatted_address;
        $ubicacion->locality = $response->result->address_components[0]->long_name;
        $ubicacion->administrative_area_level_2 = $response->result->address_components[1]->long_name;
        $ubicacion->administrative_area_level_1 = $response->result->address_components[2]->long_name;
        $ubicacion->country = $response->result->address_components[3]->long_name;;
        $ubicacion->name = $response->result->name;
        $ubicacion->lat = $response->result->geometry->location->lat;
        $ubicacion->lng = $response->result->geometry->location->lng;
        $ubicacion->place_id = $response->result->place_id;
        $ubicacion->api_id = $response->result->id;
        $ubicacion->save();
        return $ubicacion;
    }

    public function createUsuario(Request $request, $user_id, Ubicacion $ubicacion)
    {

        return Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'sexo' => $request->sexo,
            'ubicacion_id' => $ubicacion->id,
            'fecha_nac' => $request->fecha_nac,
            'user_id' => $user_id,
            'avatar' => $request->sexo == 'M' ? 'default.png' : 'default1.png'
        ]);        
    }
}
