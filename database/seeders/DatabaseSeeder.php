<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //antes de llamar hay que borrar datos existentes en la base de datos sin borrar la tabla en si.
                                                    // esto es una sentencia sql
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');//como se hacen eliminaciones desordenadas de las claves foraneas, con este comando se evitan inconsistencias
                                                    //se establecen todas las claves foraneas en cero
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();//truncando la tabla pivote

        $cantidadUsuarios = 200;
        $cantidadCategorias = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        User::factory($cantidadUsuarios)->create();
        Category::factory($cantidadCategorias)->create();
        Product::factory($cantidadProductos)->create()->each(//para cada instancia creada ejecute lo siguiente
            function($producto){//funcion anonima, recibe cada uno de los productos uno a uno y adicionalmente le enviamos las categorias 
                $categorias = Category::all()->random(mt_rand(1,5))->pluck('id');//id de un conjunto aleatorio de categorias que puede estar agrupado de manera aleatoria de 1 a 5
                // generamos las categorias manera aleatorea caso q un producto pueda tener varias categorias o 1
                // solo necesitamos la id de las categorias
                //generamos la asociacion metodo attach(recibe un array con la lista d id de las categorias q le vamos a agregar a este producto)
                $producto->categories()->attach($categorias);//optenemos la lista completa de categorias y agregamos el grupo de categorias al producto.

            }
        );
        
        Transaction::factory($cantidadTransacciones)->create();
    }
}
