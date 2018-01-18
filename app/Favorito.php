<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'publicacion_id', 'user_id', 'created_at', 'updated_at'
    ];

    public function publicacion()
    {
        return $this->belongsTo('App\Publicacion');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Usuario');
    }
}
