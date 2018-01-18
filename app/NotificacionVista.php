<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificacionVista extends Model
{
   	protected $table = 'notificaciones_vistas';
    
    protected $fillable = [ 
                            'user_id', 
                            'notificacion_id'
    					  ];
    public function user()
	{
	    return $this->belongsTo('App\User', 'user_id');
	}
	public function notificacion()
	{
	    return $this->belongsTo('App\Notificacion', 'notificacion_id');
	}
}
