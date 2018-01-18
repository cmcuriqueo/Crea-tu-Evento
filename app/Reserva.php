<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['publicacion_id', 'presupuestado', 'precio_horario', 'user_id', 'domicilio_id','fecha', 'hora_inicio', 'hora_finalizacion', 'estado', 'precio_total', 'horario_id', 'aplicar_decuento'];

    public function publicacion(){
    	return $this->belongsTo('App\Publicacion');
    }

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function mensajes(){
        return $this->belongsToMany('App\Mensaje');
    }

    public function rubros(){
    	return $this->belongsToMany('App\Rubro');
    }

    public function articulos(){
    	return $this->belongsToMany('App\Articulo')->withPivot('cantidad');
    }

    public function domicilio(){
        return $this->belongsTo('App\Domicilio');
    }

    public function horario()
    {
        return $this->belongsTo('App\Horario');
    }

    public function calificacion()
    {
        return $this->hasOne('App\Calificacion', 'reserva_id', 'id');
    }
}
