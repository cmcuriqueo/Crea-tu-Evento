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
    						'localidad_id'
    					  ];

    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }

      public function prestaciones()
    {
        return $this->hasOne('App\Prestacion');
    }

	public function localidad()
    {
        return $this->belongsTo('App\Localidad');
    }

    public function reservas(){
        return $this->hasMany('App\Reserva');
    }
}
