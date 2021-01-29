<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /**
         * llamamos al modelo Seller(que ereda de user) pero por medio de elocuentOMR
         * llamamos solo usuarios que tienen productos
         * en random se puede especificar la cantidad pero solo necesitamos 1
         */
        $vendedor = Seller::has('products')->get()->random();
        $comprador = User::all()->except($vendedor->id)->random();
        return [
            'name' => $this->faker->word,
            'quantity' => $this->faker->randomElement(1,3),
            'buyer_id' => $comprador->id,
            'product_id' => $vendedor->products->random()->id,
        ];
    }
}
