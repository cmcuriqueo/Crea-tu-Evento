<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
	 protected $table = 'calificaciones';

    protected $fillable = [
        'publicacion_id', 'reserva_id', 'calidad', 'calidad_precio', 'profesionalidad', 'respuesta', 'recomendar', 'comentario', 'puntuacion_total', 'created_at', 'updated_at', 'reportado', 'estado'
    ];


    public function publicacion()
    {
        return $this->belongsTo('App\Publicacion');
    }

    public function reserva()
    {
        return $this->belongsTo('App\Reserva');
    }
}
