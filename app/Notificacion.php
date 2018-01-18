<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
   	protected $table = 'notificaciones';
    
    protected $fillable = [ 
                            'for_role_id', 
                            'log_id',
                            'by_user_id',
                            'descripcion'
    					  ];

	public function byUser()
	{
	    return $this->belongsTo('App\User', 'by_user_id');
	}

	public function log()
	{
	    return $this->belongsTo('App\Log', 'log_id');
	}

	public function forRol()
	{
	    return $this->belongsTo('App\Rol', 'for_rol_id');
	}
}
