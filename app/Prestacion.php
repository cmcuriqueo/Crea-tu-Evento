<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestacion extends Model
{
    protected $table = 'prestaciones';

    protected $fillable = [ 
    						'proveedor_id',
    						'habilitacion',
                            'fecha_habilitacion',
    						'domicilio_id'
    					  ];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
    
    public function domicilio()
    {     
        return $this->belongsTo('App\Domicilio');    
    }

    public function rubros()
    {
        return $this->belongsToMany('App\Rubro');
    }

    public function publicaciones()
    {
        return $this->hasMany('App\Publicacion');
    }

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
