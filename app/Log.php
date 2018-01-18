<?php

namespace App;
use App\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
   	protected $table = 'logs';
    
    protected $fillable = [ 
                            'user_id', 
                            'roles_id',
                            'accion',
                            'descripcion',
                            'tabla',
                            'registro_id',
    						'valor_antiguo'
    					  ];


    static public function logs($id, $table_name, $accion, $data = null, $descripcion = null){
            $log = new Log;

            $log->user_id = $request->user()->id;
            $log->roles_id = $request->user()->roles_id;
            $log->tabla = $table_name;
            $log->accion = $accion;
            $log->descripcion = $descripcion;
            $log->registro_id = $id;
            $log->valor_antiguo = strval($data);
            $log->save();
            return $log;
        }

}
