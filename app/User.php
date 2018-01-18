<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'roles_id', 'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function usuario()
    {
        return $this->hasOne('App\Usuario');
    }

    public function mensajes()
    {
        return $this->hasMany('App\Usuario');
    }

    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }

    public function reservas()
    {
        return $this->belongsTo('App\Reserva');
    }

    public function rol()
    {     
        return $this->belongsTo('App\Rol', 'roles_id', 'id');    
    }

    public function scopePassword($query, $password){
        return $query->where('password', $password);
    }

    public function scopeActivo($query){
        return $query->where('estado', 1);
    }

    public function scopeBaja(){
        $this->estado = 0;
        return $this->save();
    }

     public function scopeBloqueo($action){
        if($action === 2 || $action === 1){
          $this->estado = $action;

          return $this->save();
        }else
          return false;

    }

      public function scopeAlta(){
          $this->estado = 1;
          return $this->save();
      }

      public function __toString(){
        return 'name: '.$this->name.', estado: '.$this->estado.', roles_id: '.$this->roles_id;
    }
}
