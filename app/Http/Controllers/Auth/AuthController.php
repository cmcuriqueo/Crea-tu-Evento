<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\User;
use Socialite;
use App\SocialAccountService;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);

        try {
            $token = JWTAuth::attempt($request->only('email', 'password'), [
                'exp' => Carbon::now()->addWeek()->timestamp,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not authenticate',
            ], 500);
        }

        if (!$token) {
            return response()->json([
                'error' => 'Could not authenticate',
            ], 401);
        } else {

            $meta = [];

            $meta['token'] = $token;
            $user = User::where('id', $request->user()->id)->with('usuario.localidad.provincia', 'role')->first();

            if ($user->estado != 2){
                if(!$user->estado) $user->alta();
                return response()->json(['data' =>  $user, 'meta' => $meta], 200);
            } else {
                    Auth::logout();
                    return response()->json([
                        'message' => 'La cuenta se ecuentra temporalmente bloqueada.',
                    ], 403);
            }
        }
    }

    public function logout(Request $request)
    {
        $token = JWTAuth::getToken();
        
        if(!$token){
            JWTAuth::invalidate($token);
            return response()->json(['data' => 'OK'], 200);
        } else {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
        
    }


    protected function validateLogin(Request $request)
    {
        return $this->validate($request, 
            [  'email' => 'required',
                'password' => 'required'
            ]);
    }

    public function getAuth(Request $request){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
        $user = User::where('id', $user->id)->with('usuario.localidad.provincia', 'role')->first();
        $meta = [];
        $token = JWTAuth::getToken();
        $meta['token'] = $token;

        return response()->json(['data' =>  $user, 'meta' => $meta, 'csrfToken' => csrf_token()]);
    }
    /**
     * Redirect the user to the google authentication page.
     *
     * @return Response
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */

    public function callback(SocialAccountService $service){

       $user = $service->createOrGetUser(Socialite::driver('google')->user());
      //return responsejson()
      if ($user){
        $token = JWTAuth::fromUser($user);
        return redirect()->to('/?token='.$token);
      }
      return redirect()->to('/');
    }

}