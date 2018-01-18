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
use App\User;
use App\Role;

class RegisterUserController extends Controller
{
    protected function validatorUsers(Request $request)
    {
      return $this->validate($request, 
        [
            'name' => 'required|min:4|max:55',
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
            'localidad_id' => 'required|exists:localidades,id',
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
        return User::create([
            'name' => $request->name,
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
        
        if(!$request->has('login') || ($request->has('login') && $request->login))
            $this->guard()->login($user);

        $this->createUsuario($request, $user->id);
        User::where('id', $user->id)->with('usuario.localidad.provincia')->first();
        return response()->json(['data' =>  $user, 'csrfToken' => csrf_token()], 200);
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

    public function createUsuario(Request $request, $user_id)
    {

        return Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'sexo' => $request->sexo,
            'localidad_id' => $request->localidad_id,
            'fecha_nac' => $request->fecha_nac,
            'user_id' => $user_id,
            'avatar' => $request->sexo == 'M' ? 'default.png' : 'default1.png'
            ]);        
    }
}
