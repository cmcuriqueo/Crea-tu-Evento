<?php

namespace App\Http\Services;
use App\Domicilio;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DomicilioService
{
    use ValidatesRequests;

    public function createDomicilio($request, $tipo)
    {
        return Domicilio::create([
            'tipo_domicilio'=> $tipo,
            'calle'=> $request->calle,
            'numero'=> $request->numero,
            'piso'=> $request->piso,
            'localidad_id'=> $request->localidad_id
            ]);
    }

    public function updateDomicilio($request, $domicilio){
        $domicilio->update([
            'calle' => $request->calle,
            'numero' => $request->numero,
            'piso' => $request->piso,
            'localidad_id' => $request->localidad_id
        ]);
    }

    public function validateDomicilio($request)
    {
        return $this->validate($request, [
            'numero' => 'required|min:1|max:10',
            'calle' =>'required|min:1',
            'localidad_id'=> 'required|exists:localidades,id'
            ]);
    }

}