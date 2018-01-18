<?php

namespace App\Http\Controllers;

use App\Http\Services\CheckCategoriesService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Subcategoria;
use App\Categoria;
use App\Rubro;
use App\Prestacion;

class RubroController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rubros = [];
        $query = Rubro::with('caracteristicas')->orderBy('nombre', 'asc');

        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('nombre','like',$like);
        }

        if( $request->has('page') || $request->has('per_page') ) 
            $rubros = $query->paginate(10);
        else
            $rubros = $query->get();

        return response()->json($rubros);
    }

    public function store(Request $request){

        //$this->validatorRubro($request);

        $this->validate($request, [
                'nombre'=>'required|min:4|max:55',
                'servicio'=>'required',
                'salon'=>'required',
                'producto'=>'required'
            ]);

        $rubro = $this->createRubro($request);
        
        if($request->has('caracteristicas') && $rubro)
        {
            $rubro->caracteristicas()->attach($request->caracteristicas);
        }

        if ($rubro)
        {
            return response($request->salon.'-'.$request->servicio.'-'.$request->producto, Response::HTTP_OK);
        } 
        else 
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function createRubro(Request $request)
    {
        return Rubro::create([
            'nombre'=> $request->nombre,
            'servicio' => $request->servicio,
            'salon' => $request->salon,
            'producto' => $request->producto
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rubros= Rubro::where('id', $id)->with('caracteristicas')->firstOrFail();

        if ($rubros) {
            return response()->json(['data' => $rubros], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
                'servicio'=>'required',
                'salon'=>'required',
                'producto'=>'required'
            ]);

        $rubro = Rubro::where('id', $id)->firstOrFail();


        $rubro->update($request->all());

        if($request->has('caracteristicas')&&($rubro))
        {   
            $ids = $request->caracteristicas;
            DB::table('caracteristica_rubro')
                            ->where('rubro_id', $id)
                            ->whereNotIn('caracteristica_id', $ids)->delete();

            $rubro->caracteristicas()->detach($request->caracteristicas);
            $rubro->caracteristicas()->attach($request->caracteristicas);

        }

        if($rubro->save())
        {
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchRubros(Request $request)
    {
        $rubros = Rubro::orderBy('nombre', 'asc')->get();


        return response()->json($rubros);
    }
}
