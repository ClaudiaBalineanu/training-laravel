<?php

use App\OrderProduct;
use App\Order;
use App\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //factory(Product::class, 50)->create();
        //factory(Order::class, 50)->create();

        // if run only the seed for pivot class it makes itself the necessary products
        factory(OrderProduct::class, 5)->create();
    }
}
