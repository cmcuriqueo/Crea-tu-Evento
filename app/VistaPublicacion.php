<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VistaPublicacion extends Model
{
    protected $table = 'vistas_publicaciones';

    protected $fillable = [ 
                            'publicacion_id', 
    						'user_id'
    					  ];
    public function publicacion()
    {
        return $this->belongsTo('App\Publicacion');
    }
}
