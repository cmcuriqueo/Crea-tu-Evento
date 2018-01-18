<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\TerminoCondicion;

class TerminoCondicionController extends Controller
{

    public function show(Request $request)
    {
    	$terminosCondiciones = TerminoCondicion::where('estado', 1)->firstOrFail();
    	return response()->json($terminosCondiciones, Response::HTTP_OK);
    }

    public function store(Request $request){
        $this->validate($request, [
                'cuerpo'=>'required|min:200|max:33000'
            ]);

       	$termCond = TerminoCondicion::create([
            'cuerpo'=> $request->cuerpo,
            'user_id' => $request->user()->id
        ]);
        TerminoCondicion::where('id', '!=', $termCond->id)->update(['estado' => 0]);

        return response(null, Response::HTTP_OK);
    }

    public function update(Request $request)
    {
		$terminosCondiciones = TerminoCondicion::where('estado', 1)->firstOrFail();
        $this->validate($request, [
                'cuerpo'=>'required|min:200|max:33000'
            ]);
        $terminosCondiciones->update(['cuerpo'=> $request->cuerpo]);
        $terminosCondiciones->save();
        return response(null, Response::HTTP_OK);
    }
}
