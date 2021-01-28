<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    public function estaDisponible()
    {
        return $this-> status == product::PRODUCTO_DISPONIBLE;
    }
    /**
     * los productos pertenecen a un vendedor
     * como solo tiene un vendedor se pone en singular(ver que las demas relaciones estan en plural)
     */

     public function seller()
     {
         return $this->belongsTo(Seller::class);
     }
     /**
      * un producto tiene muchas transacciones
      */
      public function transactions()
      {
          return $this->hasMany(Transaction::class);
      }


/**
 * Categorias tiene una relacion de muchos a muchos con productos
 */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }



}
