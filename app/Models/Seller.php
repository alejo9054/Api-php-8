<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use App\Scopes\SellerScope;

class Seller extends User
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScope);
    }
    /**
     * un vendedor tiene muchos productos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
