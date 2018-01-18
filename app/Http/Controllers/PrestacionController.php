<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\ProveedorService;
use Illuminate\Support\Facades\Auth;
use App\Proveedor;
use App\Domicilio;
use App\Prestacion;
use App\Log;

class PrestacionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$proveedor,$domicilio)
    {
        return Prestacion::create([
                    'proveedor_id'=> $proveedor->id,
                    'rubro_id'=> $request->rubro_id,
                    'habilitacion'=> $request->habilitacion,
                    'fecha_habilitacion' => $request->fecha_habilitacion,
                    'domicilio_id'=> $domicilio->id,
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
        $datosCargados= true;
        $proveedor = Proveedor::where('user_id', $request->user()->id)->firstOrFail();
        $this->validatorRubro($request);
        if ($request->comercio == true){
            $this->validatorDomicilio($request);
            $domicilio = $this->createDomicilio($request);
            $rubro= $this->create($request,$proveedor, $domicilio);
        }
        else{
            $domicilio= Domicilio::where('id', $proveedor->domicilio_id)->firstOrFail();
            $rubro= $this->create($request,$proveedor, $domicilio);
        }
        if (($rubro)&&($domicilio)){
                $datosCargados= true;
        }
        if ($rubro){
                $datosCargados= true;
        }
        if ($datosCargados){
            return response(null, Response::HTTP_OK);
        
        } else {
           return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

        /**
     * @param $request
     */
    protected function validatorDomicilio(Request $request)
    {
      return $this->validate($request, 
        [
            'calle'=>'max:55',
            'numero'=> 'max:10',
            'piso'=> 'max:10',
            'localidad_id'=> 'exists:localidades,id'
        ]);
    }

           /**
     * @param $request
     */
    public function createDomicilio(Request $request)
    {
        return Domicilio::create([
                    'tipo_domicilio'=>'Social',
                    'calle'=> $request->calle,
                    'numero'=> $request->numero,
                    'piso'=> $request->piso,
                    'localidad_id'=> $request->localidad_id
            ]);
    }



    protected function validatorRubro(Request $request)
    {

        if($request->comercio == true && $request->habilitacion !== "")
        {
            $rules["habilitacion"] ='max:55';
            $rules["fecha_habilitacion"] = 'required_with:habilitacion|date';
        }
          return $this->validate($request, $rules);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rubro= Prestacion::where('id', $id)->with('rubros','domicilio.localidad.provincia')->firstOrFail();

        if ($rubro) {
            return response()->json(['rubro' => $rubro], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $rubro = Prestacion::where('id', $id)->firstOrFail();
        $saveDomicilio = true;

        $this->validatorRubro($request);
        if ($request->comercio){
            $this->validatorDomicilio($request);
            $domicilio= Domicilio::where('id', $rubro->domicilio_id)->first(); 
            if($domicilio)
            {
                $domicilio->update($request->all());
                $saveDomicilio = $domicilio->save();
            }
            else
            {
                $domicilio= $this->createDomicilio($request);
                $saveDomicilio = true;
            }
        }
        
        $rubro->update($request->all());
        
        if($rubro->save()&& $saveDomicilio){
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
        //
    }

    public function getAll(Request $request, $id){
        $proveedor = Proveedor::where('user_id', $id)->firstOrFail();

        $rubros = Prestacion::where('proveedor_id', $proveedor->id)
            ->with('domicilio.localidad.provincia','rubros.caracteristicas')->get();
        return response()->json(['rubros' => $rubros], 200);
    }


}
