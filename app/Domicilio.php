<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domicilio extends Model
{
    protected $table = 'domicilios';

    protected $fillable = [ 'tipo_domicilio', 
    						'calle', 
    						'numero',
    						'piso',
    						'ubicacion_id'
    					  ];

    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }

      public function prestaciones()
    {
        return $this->hasOne('App\Prestacion');
    }

	public function ubicacion()
    {
        return $this->belongsTo('App\Ubicacion');
    }

    public function reservas(){
        return $this->hasMany('App\Reserva');
    }
}
