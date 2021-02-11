<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vendedores = Seller::has('products')->get();
        return response(['data' => $vendedores], 200);
    }

  
    public function show($id)
    {
        //
        $vendedores = Seller::has('products')->findOrFail($id);
        return response(['data' => $vendedores], 200);
    }

}
