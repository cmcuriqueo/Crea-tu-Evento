<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TerminoCondicion extends Model
{
    protected $table = 'terminos_condiciones';

    protected $fillable = [ 
    						'user_id',
    						'cuerpo',
    						'estado'
    					  ];

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
