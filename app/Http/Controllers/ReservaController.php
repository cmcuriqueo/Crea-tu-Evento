<?php

namespace App\Http\Controllers;

use App\Http\Services\DomicilioService;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reserva as MailReserva;
use App\Mail\ResponseProveedor;
use App\Mail\ReservaConfirmacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Publicacion;
use App\Domicilio;
use App\Horario;
use App\Mensaje;
use App\Reserva;
use App\Role;

class ReservaController extends Controller
{

    /**
     * @var DomicilioService
     */
    private $domicilioService;

    /**
     * ReservaController constructor.
     */
    public function __construct( DomicilioService $domicilioService)
    {
        $this->domicilioService = $domicilioService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservas = Reserva::where('estado', '!=','reservado')->select('fecha', 'estado')->get();

        return response()->json($reservas, Response::HTTP_OK);
    }

    public function reservasUser(Request $request, $user_id)
    {
        $reservas = Reserva::where('user_id', $user_id)->where('estado', 'confirmado')
            ->with('publicacion.proveedor','rubros','articulos')->get();

        return response()->json($reservas, Response::HTTP_OK);
    }

    public function reservasProveedor(Request $request, $id){
        if($request->user()->hasRole('provider') || $request->user()->hasRole('admin')){
            if($id == 'me' && $request->user()->hasRole('provider')){
                $id = $request->user()->proveedor->id;
            }
            $reservas = Reserva::join('publicaciones', 'publicaciones.id', '=', 'reservas.publicacion_id')
                    ->join('proveedores', 'publicaciones.proveedor_id', '=', 'proveedores.id')
                    ->where('proveedores.id', $id)->where('reservas.estado', 'confirmado')->select('reservas.*')
                    ->with('user.usuario', 'publicacion', 'rubros','articulos')->get();

            return response()->json($reservas, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
    }

    protected function validateReserva(Request $request, $is_presupuesto)
    {
        if($is_presupuesto){
            return $this->validate($request, 
                [
                    'fecha' => 'required',
                    'articulos.*.articulo_id' => 'required',
                    'rubros' => 'required',
                    'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
                ]);
        } else{
            return $this->validate($request, 
                [
                    'fecha' => 'required',
                    'articulos.*.articulo_id' => 'required',
                    'articulos.*.cantidad' => 'required|min:1',
                    'rubros' => 'required',
                    'precio_total' => 'required|regex:/^\d*(\.\d{2})?$/',
                    'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
                ]);
        }
    }

    protected function createReserva(Request $request, $publicacion, $user, $domicilio_id, $precio_total)
    {
        return Reserva::create([
                'publicacion_id' => $publicacion->id,
                'presupuestado' => 0,
                'user_id' => $user->id,
                'fecha' => $request->fecha,
                'domicilio_id' => $domicilio_id,
                'precio_total' => $precio_total,
                'estado' => $request->estado
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $publicacion_id)
    {
        $requiredDomicilio = false;
        $precio_total = 0;

        $publicacion = Publicacion::where('id', $publicacion_id)
                ->with('prestacion.rubros', 'prestacion.domicilio', 'articulos')->firstOrFail();

        $user = $request->user();

        $this->validateReserva($request, false);

        foreach ($publicacion->prestacion->rubros as $rubro) { 
            if($rubro->salon){
                $requiredDomicilio = false;
                break;
            }
        }

        foreach($request->articulos as $articuloSelect){
            foreach ($publicacion->articulos as $articulo) {
                if($articuloSelect['articulo_id'] == $articulo->id){
                    $precio_total = $precio_total + ($articulo->precio * $articuloSelect['cantidad']);
                }
            }
        }

        // precio mas precio horario

        if( $requiredDomicilio ){
            $this->domicilioService->validateDomicilio($request);
            $domicilio = $this->domicilioService->createDomicilio($request, 'Social');
        } else {
            $domicilio = Domicilio::where('id', $publicacion->prestacion->domicilio_id )->firstOrFail();
        }

        $reserva = $this->createReserva($request, $publicacion, $user, $domicilio->id, $precio_total);
        $reserva->articulos()->attach($request->articulos);
        $reserva->rubros()->attach($request->rubros);

        if( $reserva->save() )
        {
            return response(null, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function storePresupuesto(Request $request, $publicacion_id)
    {
        $requiredDomicilio = false;
        $domicilio = null;
        $horario = null;

        $publicacion = Publicacion::where('id', $publicacion_id)
                ->with('prestacion.rubros', 'prestacion.domicilio', 'articulos', 'proveedor')->firstOrFail();

        $user = $request->user();

        $this->validateReserva($request, true);

        if($request->has('horario_id') && $request->horario_id != null)
        {
            $horario = Horario::where('id', $request->horario_id)->firstOrFail();;
        }

        foreach ($publicacion->prestacion->rubros as $rubro) { 
            if($rubro->salon){
                $requiredDomicilio = false;
                break;
            }
        }

        if( $requiredDomicilio ){
            $this->domicilioService->validateDomicilio($request);
            $domicilio = $this->domicilioService->createDomicilio($request, 'Social');
        } else {
            $domicilio = Domicilio::where('id', $publicacion->prestacion->domicilio_id )->firstOrFail();
        }

        $reserva = $this->createReserva($request, $publicacion, $user, $domicilio->id, 0.00);
        $reserva->articulos()->attach($request->articulos);
        $reserva->rubros()->attach($request->rubros);

        if($horario != null)
        {
            $reserva->horario_id = $horario->id;
            $reserva->hora_inicio = $horario->hora_inicio;
            $reserva->hora_finalizacion = $horario->hora_fin;
            $reserva->precio_total = $horario->precio;
        }

        $mensaje = Mensaje::create([ 
            'from_user_id' => $request->user()->id, 
            'to_user_id' => $publicacion->proveedor->user_id, 
            'ancestro_id' => null, 'mensaje' => $request->comentario, 
            'reserva_id' => $reserva->id
        ]);

        if( $reserva->save() )
        {
            try {
                Mail::to($publicacion->proveedor->email)->queue(new MailReserva($reserva));
            } catch(Exception $e){
                    Logging::warning($e->getMessage());
            }
            return response(null, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function cambiarEstado(Request $request, $id)
    {
        $this->validate($request, [
                'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
            ]);
        $reserva = Reserva::where('id', $id)->with('user.usuario', 'publicacion.proveedor', 'rubros','articulos')->firstOrFail();
        $yaReservado = Reserva::where('publicacion_id', $reserva->publicacion_id)
            ->where('fecha', $reserva->fecha)->where('horario_id', $reserva->horario_id)->where('id', '!=', $reserva->id)->first();

        if($request->user()->id == $reserva->user_id)
        {
            if($yaReservado){
                return response(null, Response::HTTP_CONFLICT);
            } else {
                DB::beginTransaction();

                try {
                    $reserva->update(['estado' => $request->estado]);
                    DB::commit();
                    if($request->estado == 'confirmado'){
                        try {
                            Mail::to($reserva->publicacion->proveedor->email)->queue(new ReservaConfirmacion($reserva));
                        } catch(Exception $e){
                            Logging::warning($e->getMessage());
                        }
                    }
                    return response(null, Response::HTTP_OK);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
                }

            }
        }
        else
        {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reserva = Reserva::where('id', $id)->with('publicacion','user','rubros','articulos', 'domicilio.ubicacion', 'horario')->firstOrFail();
        return response()->json($reserva, Response::HTTP_OK);
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePresupuesto(Request $request, $id)
    {
        $reserva = Reserva::where('id', $id)->where('estado', 'presupuesto')
                ->with('user.usuario', 'publicacion.proveedor')->firstOrFail();
        $reserva->articulos()->detach();
        $reserva->articulos()->attach($request->articulos);

        if($request->user()->hasRole('provider'))
        {
            $reserva->presupuestado = true;
            $reserva->precio_total = $request->precio_total;
            $reserva->aplicar_decuento = $request->aplicar_decuento;
        }
        else  {
            $horario = null;
            if($request->has('horario_id') && $request->horario_id != null)
            {
                $horario = Horario::where('id', $request->horario_id)->firstOrFail();;
            }

            $reserva->presupuestado = false;
            if($horario != null){
                $reserva->horario_id = $horario->id;
                $reserva->hora_inicio = $horario->hora_inicio;
                $reserva->hora_finalizacion = $horario->hora_fin;
            }
            $reserva->update(['fecha' => $request->fecha]);
            Mensaje::create([ 
                'from_user_id' => $request->user()->id, 
                'to_user_id' => $reserva->publicacion->proveedor->user_id, 
                'ancestro_id' => null, 'mensaje' => $request->comentario, 
                'reserva_id' => $reserva->id
            ]);
        }
       
        
        if( $reserva->save() )
        {
            if($request->user()->hasRole('provider'))
            {
                try {
                    Mail::to($reserva->publicacion->proveedor->email)->queue(new ResponseProveedor($reserva));
                } catch(Exception $e){
                    Logging::warning($e->getMessage());
                }
            }
            return response(null, Response::HTTP_OK);
        }
        else 
        {
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
}
