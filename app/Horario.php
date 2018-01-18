<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    
    protected $fillable = [ 
                            'hora_inicio',
                            'hora_fin',
    						'dia',
    						'precio',
    						'publicacion_id',
                            'proveedor_id',
    						'created_at', 'updated_at'
    					  ];


    public function publicaciones()
    {
        return $this->belongsTo('App\Publicacion');
    }

    public function reservas()
    {
        return $this->hasMany('App\Reserva');
    }
}
