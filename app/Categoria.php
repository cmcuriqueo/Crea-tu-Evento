<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [ 
                            'nombre',
            				'tipo_proveedor'
    					  ];

    
    public function subcategorias()
    {
        return $this->hasMany('App\Subcategoria');
    }

}
