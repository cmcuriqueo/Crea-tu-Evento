<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    
    protected $fillable = ['formatted_address', 'locality', 'administrative_area_level_2', 'administrative_area_level_1', 'country', 'name', 'lat', 'lng', 'place_id','api_id'  ];


    ///RELACIONES//
    public function usuario()
    {
        return $this->hasOne('App\Usuario');
    }

    public function domicilios()
    {
        return $this->hasMany('App\Domicilio');
    }
}
