<?php

namespace App\Http\Services;
use App\Prestacion;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PrestacionService
{
    use ValidatesRequests;

    public function createPrestacion($request, $proveedor, $domicilio){
        $prestacion = Prestacion::create([
            'proveedor_id'=> $proveedor->id,
            'habilitacion'=> $request->habilitacion,
            'fecha_habilitacion' => $request->fecha_habilitacion,
            'domicilio_id'=> $domicilio->id
        ]); 
        return $prestacion;
    }

    public function updatePrestacion($request, $prestacion){
        $prestacion->update([
            'habilitacion'=> $request->habilitacion,
            'fecha_habilitacion' => $request->fecha_habilitacion
        ]);
    }

    public function validatePrestacion($request)
    {
        $rules["rubros_id"] = 'required';
        if($request->comercio == true && $request->habilitacion !== "")
        {
            $rules["habilitacion"] ='max:55';
            $rules["fecha_habilitacion"] = 'required_with:habilitacion|date';
        }
        return $this->validate($request, $rules);
    }
}