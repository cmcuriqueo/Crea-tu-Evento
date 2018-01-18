<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    
    protected $table = 'usuarios';

    protected $fillable = [ 
                            'avatar', 
    						'nombre', 
    						'apellido', 
    						'fecha_nac',
                            'sexo',
    						'user_id',
    						'localidad_id'
    					  ];


    ///RELACIONES//
	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function localidad()
    {
        return $this->belongsTo('App\Localidad');
    }

    public function __toString(){
    	return 'Imagen:'.$this->avatar.', nombre:'.$this->nombre.', '.$this->apellido.', fecha nacimiento:'.$this->fecha_nac.', sexo:'.$this->sexo.', localidad: '.$this->localidad_id;
    }
}
