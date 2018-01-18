<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Log;
use App\User;

class LogController extends Controller
{
	
    public function show(Request $request, $id){
    	
    	$actividad = Log::where('id', $id)->firstOrFail();

    	if (Gate::allows('show-log', $actividad)) {
	    	if($actividad->tabla === 'users') {
	    		$user = User::where('id', $actividad->registro_id)->with('usuario', 'rol')->first();
	    		return response()->json([ 'data' => $actividad, 'user' => $user], 200);
	    	}
	    	else
	    		return response()->json(['data' => $actividad], 200);
	    } else {
	    	return response(null, Response::HTTP_FORBIDDEN);
	    }
    }
}
