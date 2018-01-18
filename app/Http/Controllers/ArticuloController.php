<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Articulo;
use App\Proveedor;
use App\Role;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = null;
        $user = $request->user();
        $articulos = [];
        if($user->hasRole('provider') || $user->hasRole('admin')
         || $user->hasRole('supervisor')){
            if($request->has('user_id'))
            {
                $user_id = $request->user_id;
            } 
            else 
            {
                $user_id = $user->id;
            }

            $proveedor = Proveedor::where('user_id', $user_id)->firstOrFail();
            $query = Articulo::where('proveedor_id', $proveedor->id)->with('rubro')
                ->orderBy('rubro_id', 'asc')->orderBy('nombre', 'asc');

            if($request->has('filter') && $request->filter != ''){
                $like = '%'.$request->filter.'%';
                $query->where(function($query) use ($like){
                        $query->where('nombre','like', $like );
                    });
            }

            if($request->has('page') || $request->has('per_page')){
                $articulos = $query->paginate(10);
            }else{
                $articulos = $query->get();
            }

            return response()->json($articulos, 200);

        }        
        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateArticulo($request);

        $user = $request->user();
        if($user->hasRole('provider')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

            $articulo = Articulo::create([
                'proveedor_id' => $proveedor->id,
                'nombre' => $request->nombre,
                'stock' => $request->stock,
                'rubro_id' => $request->rubro_id,
                'precio' => $request->precio,
                'estado' => 1
            ]);

            if($articulo->save())
            {
                return response(null, Response::HTTP_OK);
            }
            else
            {
                return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            } 
        }

        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    protected function validateArticulo(Request $request)
    {
        return $this->validate($request, 
            [
                'rubro_id' => 'required', 
                'stock' => 'numeric|nullable', 
                'nombre' => 'required|min:3|max:25',
                'precio' => 'nullable'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $articulo = Articulo::with('publicaciones')->where('id', $id)->where('estado', 1)->firstOrFail();

        return response()->json($articulo, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, 
            [
                'stock' => 'numeric|nullable', 
                'nombre' => 'required|min:3|max:25',
                'precio' => 'nullable'
            ]);
        $user = $request->user();
        if($user->hasRole('provider')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

            $articulo = Articulo::where('id', $id)->where('proveedor_id', $proveedor->id)->firstOrFail();

            $articulo->update($request->all());
            
            if($articulo->save())
            {
                return response()->json($articulo, Response::HTTP_OK);
            }
            else
            {
                return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
    }
}
