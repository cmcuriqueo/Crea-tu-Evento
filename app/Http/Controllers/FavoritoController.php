<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Favorito;

class FavoritoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Favorito::create([
                    'user_id' => $request->user_id,
                    'publicacion_id'=> $request->publicacion_id
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $favoritos = Favorito::where('publicacion_id', $request->publicacion_id)->where('user_id', $request->user_id)->first();
        if ($favoritos){
            $favoritos->delete();
              return response()->json(['favoritos' => null], 200);
        }else{
             $favoritos = $this->create($request);
            if ($favoritos){
                return response()->json(['favoritos' => $favoritos], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $favoritos = Favorito::join('publicaciones', 'favoritos.publicacion_id', '=', 'publicaciones.id')
                ->where('publicaciones.estado', 1)
                ->where('user_id', $id)->get()->pluck('publicacion_id');
            return response()->json(['data' => $favoritos], 200);
            
        
    }

}
