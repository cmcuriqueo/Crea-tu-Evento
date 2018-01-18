<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Horario;
use App\Reserva;
use App\Publicacion;
use App\Proveedor;
use App\Rol;
use Carbon\Carbon;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //$horarios= Horario::where('id', $id)->get();

         $proveedor= Proveedor::where('user_id', $id)->firstOrFail();
        $horario = Horario::where('publicacion_id', null)
                 ->where('proveedor_id', $proveedor->id)->get();
               

        if ($horario) {
            return response()->json( $horario, 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function indexPublicacion($publicacion_id)
    {
        $horarios= Publicacion::where('id', $publicacion_id)->with('horarios')->get();

        if ($horarios) {
            return response()->json( $horarios[0], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateHorario($request);
        $dias = $request->dia;
        $j = 0;
        $horarioNo = [];
        $idHorarios = [];
        $save=false;
        $user = $request->user();
        $horarioRepetido= null;
        $proveedor= Proveedor::where('user_id', $user->id)->firstOrFail();
        if ($request->horariosId != []){
                Horario::where('publicacion_id', null)
                            ->where('proveedor_id', $proveedor->id)
                            ->whereNotIn('id', $request->horariosId)->delete();
        }else{
                Horario::where('publicacion_id', null)
                            ->where('proveedor_id', $proveedor->id)->delete();
        } 
         
        for ($i=0; $i < sizeof($dias); $i++) {
            $diaUnico = $dias[$i];
            $horarioRepetido = Horario::where('publicacion_id', $request->publicacion_id)
                            ->where('proveedor_id', $proveedor->id)
                            ->where('dia', $diaUnico)
                            ->where(function ($q) use ($request){
                                    $q->where('hora_inicio', '>=',$request->hora_inicio)
                                    ->where('hora_inicio', '<', $request->hora_fin)
                                    ->orWhere(function ($q) use ($request){
                                            $q->where('hora_fin', '>',$request->hora_inicio)
                                            ->where('hora_fin', '<=', $request->hora_fin)->get();
                                        })->get();
                            })->first();
        
                   if ($horarioRepetido) {
                         $horarioNo[$j]= $horarioRepetido;
                         $j = $j+1;
                   }
                   else{
                        $horario = Horario::create([
                            'dia' => $diaUnico,
                            'hora_inicio' => $request->hora_inicio,
                            'hora_fin' => $request->hora_fin,
                            'publicacion_id' => $request->publicacion_id,
                            'proveedor_id' => $proveedor->id,
                            'precio' => $request->precio
                        ]);
                        if($horario->save()){
                            $horarios[$i]=$horario;
                            $idHorarios [$i] = $horario->id;
                            $save=true;
                        }else{
                            $save=false;
                            $horarioNo [$j]= $horario;
                            $j = $j+1;
                        }
                    }
        }
        if($save){
                return response(['horarioNo' => $horarioNo, 'idHorario' => $idHorarios], Response::HTTP_OK);
            }
            else {
                return response(['horarioNo' => $horarioNo], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    protected function validateHorario(Request $request)
    {
        return $this->validate($request, 
            [
                'dia'=>'required',  
                'hora_inicio' => 'required', 
                'hora_fin' => 'nullable',
                'precio' => 'nullable'
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
         $horario= Horario::where('id', $id)->first();

        if ($horario) {
            return response()->json( $horario, 200);
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
        
        $this->validateHorario($request);
        $user = $request->user();
        $horarioRepetido = null;
        if($user->roles_id == Rol::roleId('proveedor')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();
            $publicacionId = null;
            $horario = Horario::where('id', $id)->firstOrFail();
            if ($request->publicacion_id != null){
                $publicacionId= Publicacion::where('id', $horario->publicacion_id)->where('proveedor_id', $proveedor->id)->firstOrFail()->puck('id');
            }else{
                $publicacionId = null;
            }
            if ($publicacionId||$proveedor){
                //verificar horario si se encuentra repetido
                $horarioRepetido = Horario::where('publicacion_id', $publicacionId)
                            ->where('proveedor_id', $proveedor->id)
                            ->where('dia', $horario->dia)
                            ->where('id','<>', $horario->id)
                            ->where(function ($q) use ($request){
                                    $q->where('hora_inicio', '>',$request->hora_inicio)
                                    ->where('hora_inicio', '<', $request->hora_fin)
                                    ->orWhere(function ($t) use ($request){
                                            $t->where('hora_fin', '>',$request->hora_inicio)
                                            ->where('hora_fin', '<', $request->hora_fin)->get();
                                    })->get();
                            })->first();

                if ($horarioRepetido == null){
                    $horario->update([
                        'dia' => $request->dia,
                        'hora_inicio' => $request->hora_inicio,
                        'hora_fin' => $request->hora_fin,
                        'proveedor_id' => $proveedor->id,
                        'publicacion_id' => $request->publicacion_id,
                        'precio' => $request->precio
                    ]);
                }
                if($horario->save()&&($horarioRepetido == null))
                {
                    return response()->json($horario, Response::HTTP_OK);
                }
                else
                {
                    return response()->json(['horarioRepetido' => $horarioRepetido], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }
        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    public function horariosPorFecha(Request $request, $publicacion_id, $fecha){
        $dia = $this->toDayOfWeek($fecha);
        $horarios = Horario::where('publicacion_id', $publicacion_id)->where('dia', $dia)->get();
        $reserva_id = null;
        $auxHorarios = array();
        $auxHorario = null;

        if($request->has('except_this_reserva'))
        {
            $reserva_id = $request->except_this_reserva;
        }
        if($horarios->isNotEmpty()) {
            $reservas = Reserva::where('publicacion_id', $publicacion_id)
                ->where(function ($query) {
                    $query->where('estado', 'reservado')
                      ->orWhere('estado', 'confirmado');
                })
                ->whereDate('fecha', '=', Carbon::createFromFormat('Y-m-d', $fecha)->toDateString())->get();

            foreach ($horarios as $horario) {
                $esta = false;
                foreach ($reservas as $reserva) {
                    if($reserva->horario_id == $horario->id)
                    {
                        $auxHorario = $horario;
                        if($reserva_id != null && $reserva_id == $reserva->id){
                            $auxHorario->estado = 'disponible';
                        }
                        else
                        {
                            $auxHorario->estado = 'reservado';
                        }
                        $auxHorarios[] = $auxHorario;
                        $auxHorario = null;
                        $esta = true;
                        break;
                    }
                }
                if(!$esta){
                    $auxHorario = $horario;
                    $auxHorario->estado = 'disponible';
                    $auxHorarios[] = $auxHorario;
                    $auxHorario = null;
                }
            }
        } else {
            $reserva = null;
            $reserva = Reserva::where('publicacion_id', $publicacion_id)
                ->where(function ($query) {
                    $query->where('estado', 'reservado')
                      ->orWhere('estado', 'confirmado');
                })
                ->whereDate('fecha', '=', Carbon::createFromFormat('Y-m-d', $fecha)->toDateString())->first();

            if($reserva == null){
                return response()->json(['estado' => 'disponible'], 200);
            } else {
                if($reserva_id != null && $reserva_id == $reserva->id)
                    return response()->json(['estado' => 'disponible'], 200);
                else
                    return response()->json(['estado' => 'no disponible'], 200);
            }
        }
        
        return response()->json($auxHorarios, 200);
    }

    protected function toDayOfWeek($date){
        $dt = Carbon::createFromFormat('Y-m-d', $date);
        $dayOfWeek = $dt->format('l'); 
        $dia = '';
        switch ($dayOfWeek) {
            case 'Monday':
                $dia = 'lunes';
                break;
            case 'Tuesday':
                $dia = 'martes';
                break;
            case 'Wednesday':
                $dia = 'miercoles';
                break;
            case 'Thursday':
                $dia = 'jueves';
                break;
            case 'Friday':
                $dia = 'viernes';
                break;
            case 'Saturday':
                $dia = 'sabado';
                break;
            case 'Sunday':
                $dia = 'domingo';
                break;
            default:
                break;
        }
        return $dia;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $horario= Horario::where('id', $id)->firstOrFail();

        if ($horario->delete()) {
            return response(null, Response::HTTP_OK);
        }else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
