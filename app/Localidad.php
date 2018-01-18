<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = 'localidades';
    
    protected $fillable = [ 
                            'provincia_id', 
    						'nombre'
    					  ];


    ///RELACIONES//
    public function usuario()
    {
        return $this->hasOne('App\Usuario');
    }

    public function provincia()
    {
        return $this->belongsTo('App\Provincia');
    }

    public function domicilios()
    {
        return $this->hasMany('App\Domicilio');
    }
}
