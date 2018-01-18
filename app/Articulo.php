<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['proveedor_id', 'stock', 'nombre', 'precio', 'rubro_id', 'estado'];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function publicaciones()
    {
        return $this->belongsToMany('App\Publicacion');
    }

    public function rubro()
    {
        return $this->belongsTo('App\Rubro');
    }

    public function reservas(){
        return $this->belongsToMany('App\Reserva');
    }
}
