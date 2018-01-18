<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
	protected $table = 'fotos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'publicacion_id', 'nombre', 'created_at', 'updated_at'
    ];

    public function publicacion()
    {
        return $this->belongsTo('App\Publicacion');
    }
}
