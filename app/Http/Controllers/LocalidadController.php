<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Localidad;
use App\Provincia;

class LocalidadController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Localidad::with('provincia');

        if($request->filter){
               $query= $query->join('provincias', 'localidades.provincia_id', '=', 'provincias.id')
                ->select('localidades.*')
                ->where('localidades.nombre','like' ,'%'.$request->filter.'%')
                ->orWhere('provincias.nombre','like' ,'%'.$request->filter.'%')
                ->orderBy('localidades.nombre', 'asc');
        }
        $localidades = $query->paginate(15);
        if ($localidades){
            return response($localidades, Response::HTTP_OK);
        } else {
           return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function provincias(Request $request)
    {
        $provincias = DB::table('provincias')
            ->select('id as value', 'nombre as label')
                ->orderBy('nombre', 'asc')
                ->get();
        return response()->json(['data' =>  $provincias->toArray()], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Localidad::create([
                    'nombre'=> $request->nombre,
                    'provincia_id'=> $request->provincia_id
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'nombre'=>'required|min:4|max:55',
                'provincia_id'=> 'required|exists:provincias,id'
            ]);
    
        $localidad= $this->create($request);
        
        if ($localidad){
            return response($localidad, Response::HTTP_OK);
        } else {
           return response($localidad, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showlocalidad($id)
    {
        $localidades = DB::table('localidades')
            ->join('provincias', 'localidades.provincia_id', '=', 'provincias.id')
            ->select('localidades.id as value', DB::raw('CONCAT(localidades.nombre, " (",provincias.nombre, ")") as label'))
                ->where('localidades.id', $id)
                ->get();
        return response()->json(['data' =>  $localidades->toArray()]);
    }

     public function show($id)
     {
        $localidad= Localidad::where('id', $id)->with('provincia')->firstOrFail();

        if ($localidad) {
            return response()->json(['localidad' => $localidad], 200);
        }
    }

    /**
     * Show the List Localidades.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list_options(Request $request)
    {
        $localidades = DB::table('localidades')
            ->join('provincias', 'localidades.provincia_id', '=', 'provincias.id')
            ->select('localidades.id as value', DB::raw('CONCAT(localidades.nombre, " (",provincias.nombre, ")") as label'))
                ->where('localidades.nombre','like' ,'%'.$request->q.'%')
                ->orderBy('localidades.nombre', 'asc')
                ->get();

        return response()->json(['data' =>  $localidades->toArray()], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
                'nombre'=>'required|min:4|max:55',
                'provincia_id' => 'required|exists:provincias,id'
            ]);
        //$table_name= "Provincia";
        //$accion = "update";
        $localidad= Localidad::where('id', $id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $localidad, 'Ha actualizado informacion de localidad');
        $localidad->update($request->all());
        if($localidad->save()){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $localidad= Localidad::where('id', $id)->firstOrFail();

        if ($localidad->delete()) {
            return response(null, Response::HTTP_OK);
        }else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
