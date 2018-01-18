<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = [ 'nombre',
    						'descripcion'
    					  ];


    ///RELACIONES//
    public function user()
    {
      return $this->hasMany('App\User');
    }
    //Asignacion de Roles
    public function scopeAdministrador($query)
    {
      return $query->where('nombre','LIKE','%Administrador%')->get()->pluck('id')->first();
    }
     public function scopeSupervisor($query)
    {
      return $query->where('nombre','LIKE','%Supervisor%')->get()->pluck('id')->first();
    }
     public function scopeOperador($query)
    {
      return $query->where('nombre','LIKE','%Operador%')->get()->pluck('id')->first();
    }
     public function scopeProveedor($query)
    {
      return $query->where('nombre','LIKE','%Proveedor%')->get()->pluck('id')->first();
    }
    public function scopeUsuario($query)
    {
      return $query->where('nombre','LIKE','%Usuario%')->get()->pluck('id')->first();
    }
    public function scopeRoleId($query, $role){
      return $query->where('nombre','LIKE','%'.$role.'%')->get()->pluck('id')->first();
    }
}