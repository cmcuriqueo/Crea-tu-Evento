<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = 'subcategorias';

    protected $fillable = [ 
                            'nombre',
    						'categoria_id',
    					  ];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }

    public function publicaciones()
    {
    	return $this->hasMany('App\Publicacion');
    }

}
