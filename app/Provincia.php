<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias';
    
    protected $fillable = ['nombre'];


    ///RELACIONES//
    public function localidad()
    {
        return $this->hasMany('App\Localidad');
    }
}
