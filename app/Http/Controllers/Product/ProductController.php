<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::all();

        return $this->showAll($products);
    }

  
    public function show(Product $product)
    {
        //como ya se tiene la inyeccion implisita solo debemos hacer el return
        return $this->showOne($product); 
    }

   
}
