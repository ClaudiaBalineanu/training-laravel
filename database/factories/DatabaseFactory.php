<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use App\OrderProduct;
use App\Product;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// factory for products table

$factory->define(Product::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->sentence,
        'price' => $faker->randomFloat(2, 0, 1000),
        'image' => '1.png',
    ];
});

$factory->define(Order::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'comment' => $faker->paragraph,
    ];
});

$factory->define(OrderProduct::class, function (Faker $faker) {
    return [
        'order_id' => factory(Order::class),
        'product_id' => factory(Product::class),
    ];
});
