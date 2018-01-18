<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
	protected $table = 'publicaciones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'oferta', 'descuento', 'descripcion', 'estado','proveedor_id', 'subcategoria_id', 'precio', 'fecha_finalizacion', 'prestacion_id', 'created_at', 'updated_at'
    ];

    public function fotos()
    {
        return $this->hasMany('App\Foto');
    }

    public function subcategoria()
    {
        return $this->belongsTo('App\Subcategoria');
    }

    public function reservas()
    {
        return $this->hasMany('App\Reserva');
    }

    public function mensajes(){
        return $this->hasMany('App\Mensaje');
    }

    public function prestacion()
    {
        return $this->belongsTo('App\Prestacion');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function articulos()
    {
        return $this->belongsToMany('App\Articulo');
    }

    public function caracteristicas()
    {
        return $this->belongsToMany('App\Caracteristica')->withPivot('informacion');
    }

    public function scopeOfProveedor($query, $proveedor_id){
        return $query->join('prestaciones', 'prestaciones.id','=','publicaciones.prestaciones_id')
                ->join('proveedores', 'proveedores.id','=','prestaciones.proveedor_id')
                ->where('proveedores.id', '=', $proveedor_id)
                ->select('publicaciones.*');
    }

    public function scopeDarBaja($query) {
        $query->update(['publicaciones.estado' => 0]);
    }

    public function scopeDarAlta($query) {
        $query->update(['publicaciones.estado' => 1]);
    }

    public function caracteristicasCount()
    {
        return $this->belongsToMany('App\Caracteristica')->selectRaw('count(caracteristicas.id) as caracteristicas_count');
    }

       public function favoritos()
    {
        return $this->hasMany('App\Favorito');
    }

    public function horarios()
    {
        return $this->hasMany('App\Horario');
    }

    public function calificaciones()
    {
        return $this->hasMany('App\Calificacion');
    }

    public function vistas()
    {
        return $this->hasMany('App\VistaPublicacion');
    }
}
