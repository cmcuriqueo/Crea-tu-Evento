<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $table = 'caracteristicas';

    protected $fillable = [ 
                            'nombre',
    						'adicional'
    					  ];

    
    public function rubros()
    {
        return $this->belongsToMany('App\Rubro');
    }

    public function publicaciones()
    {
        return $this->belongsToMany('App\Publicacion')->withPivot('informacion');
    }
    
}
