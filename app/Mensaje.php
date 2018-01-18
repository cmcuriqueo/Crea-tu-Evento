<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
   	protected $table = 'mensajes';
    
    protected $fillable = [ 
                            'from_user_id', 
                            'to_user_id',
                            'ancestro_id',
                            'mensaje',
                            'reserva_id',
                            'estado',
                            'lectura'
    					  ];

    public function fromUser(){
    	return $this->belongsTo('App\User', 'from_user_id');
    }

    public function toUser(){
    	return $this->belongsTo('App\User', 'to_user_id');
    }

    public function reserva(){
        return $this->belongsTo('App\Reserva');
    }

    public function ancestros(){
    	return $this->belongsTo('App\Mensaje', 'ancestro_id');
    }
}
