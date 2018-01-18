<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notificacion;

use App\NotificacionVista;
use App\Role;

class NotificacionesController extends Controller
{
    public function index(Request $request){

    }

    public function indexOfUser(Request $request, $id){
    	if($id == 'me'){
    		$id = $request->user()->id;
    	}
    	$vistas_ids = NotificacionVista::where('user_id', $request->user()->id)->get()->pluck('notificacion_id');
    	$notificaciones = Notificacion::where('for_role_id', $request->user()->roles_id)
    		->whereNotIn('id', $vistas_ids)->with('log', 'byUser')->orderBy('created_at','DESC')->limit(5)->get();

    	return response()->json($notificaciones, Response::HTTP_OK);

    }

    public function show(Request $request, $id){
        $notificacion = Notificacion::where('id', $id)->firstOrFail();
        $notificacionvista = NotificacionVista::create(['user_id' => $request->user()->id, 'notificacion_id' => $notificacion->id]);
        if($notificacionvista->save()){
            return response(null, Response::HTTP_OK);
        }
        else {
            return response()->json($notificacion, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
