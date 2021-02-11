<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends ApiController
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
        return $this->showAll($compradores);
    }

   
    public function show($id)
    {
        //
        $comprador = Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($comprador);
    }

}
