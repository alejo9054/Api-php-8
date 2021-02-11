<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //traemos unicamente los usuarios que tengan transacciones
        $compradores = Buyer::has('transactions')->get();
        return response(['data' => $compradores], 200);
    }

   
    public function show($id)
    {
        //
        $compradores = Buyer::has('transactions')->findOrFail($id);
        return response(['data' => $compradores], 200);
    }

}
