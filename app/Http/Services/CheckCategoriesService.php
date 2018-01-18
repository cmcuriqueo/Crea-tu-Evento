<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\DB;

class CheckCategoriesService
{

    /**
    * @param  $categoria_id  
     * Elimina la categoria dada por parametro 
     * si esta no esta relacionada a ninguna subcategoria.
     *
     */
	public function checkCategories($categoria_id=null)
	{
		if($categoria_id)
		{
			$categoria = DB::table('categorias')
				->select(DB::raw('count(subcategorias.categoria_id) as quantity'), 'categorias.id')
				->join('subcategorias', 'categorias.id', '=', 'subcategorias.categoria_id')
				->where('categorias.id', $categoria_id)->first();

			if($categoria->quantity == 0)
			{
				DB::table('categorias')->where('categorias.id', $categoria_id)->delete();
			}
		}
	}

}