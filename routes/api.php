<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
/*las rutas nos dicen que metodo y  cual controlador se va a ejecutar segun la peticion
realizada por el cliente o el usuario
puede variar segun el metodo http 
*/

/**
 * Buyers
 * aqui solo permitimos ver a los compradores ['index','show']
 */


Route::resource('buyers', 'Buyer\BuyerController' , ['only' => ['index','show']]);

/**
 * Categories
 * Todas menos create y edit
 */
Route::resource('categories', 'Category\CategoryController' , ['except' => ['create','edit']]);
/**
 * Products
 */
Route::resource('products', 'Product\ProductController' , ['only' => ['index','show']]);
/**
 * Transactions
 */
Route::resource('transactions', 'Transaction\TransactionController' , ['only' => ['index','show']]);
/**
 * Sellers
 */
Route::resource('sellers', 'Seller\SellerController' , ['only' => ['index','show']]);
/**
 * Users
 * todas menos create y edit
 */
Route::resource('users', 'User\UserController' , ['except' => ['create','edit']]);