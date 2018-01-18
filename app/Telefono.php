<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $table = 'telefonos';

    protected $fillable = [ 'cod_area', 
    						'numero'
    					  ];


    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }
}
