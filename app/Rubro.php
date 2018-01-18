<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = [ 
                            'nombre',
                            'salon',
                            'servicio',
                            'producto'
    					  ];
    
    ///RELACIONES//

    public function prestaciones()
    {
        return $this->belongsToMany('App\Prestacion')->withPivot('cant_horas', 'precio');
    }

    public function caracteristicas()
    {
        return $this->belongsToMany('App\Caracteristica');
    }

    public function articulos(){
        return $this->hasMany('App\Articulo');
    }
   
    public function reservas()
    {
        return $this->belongsToMany('App\Reserva');
    }

}