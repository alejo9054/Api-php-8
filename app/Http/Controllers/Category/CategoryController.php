<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::all();

        return $this->showAll($categories);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];
        $this->validate($request, $rules);//la funcion validate verifica que se esten cumpliendo las reglas, de lo contrario va a disparar una excepcion 
        $category = Category::create($request->all());

        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
        return $this->showOne($category);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //se debe recibir almenos uno de los 2 parametros ya sea nombre o descripcion
        // el metodo fill hace lo que necesitamos resibiendo los valores que vamos a interceptar
        //el metodo only() obtiene unicamente lso valores de nombre  y descripcion que requerimos, si sen evia otro valor no sera tenido en cuenta para la actualizacion
        $category->fill($request->only([
            'name',
            'description',
        ]));
        // ahora verificamos si cambio algo de lo que se tenia anteriormente
        // isDirty verifica si ha cambiado()  e isClean() hace lo contrario
        if ($category->isClean()){// si no ha cambiado da un error
            return $this->errorResponse('debe especificar al menos un valor diferente para actualizar',422);
        }
        $category->save(); // se guardan las modificaciones

        return $this->showOne($category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();

        return $this->showOne($category);
    }
}
