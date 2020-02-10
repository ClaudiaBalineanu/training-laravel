<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/test', function () {
    return view('test');
});
*/

Route::get('/index', 'ProductsController@index')->name('index');
Route::get('/add-to-cart/{product}', 'ProductsController@addToCart')->name('addToCart.product');

Route::get('/cart', 'ProductsController@cart')->name('cart');
Route::get('/remove-from-cart/{product}', 'ProductsController@removeFromCart')->name('removeFromCart.product');

Route::post('/cart', 'ProductsController@checkoutCart')->name('checkout');



//Route::post('/login', 'LoginController@store');
//Route::get('/login', 'LoginController@create')->middleware('auth');


Route::get('/products', function () {
    //$product = App\Product::all();
    return view('products', [
        'products' => App\Product::all() // take(3)->latest()->get()
    ]);
})->name('products');

Route::post('/products', 'ProductsController@store')->name('store');
Route::get('/products/create', 'ProductsController@create')->name('create');

Route::put('/products/{product}', 'ProductsController@update')->name('update');
Route::get('/products/{product}/edit', 'ProductsController@edit')->name('edit');


Route::get('/products/delete/{product}', 'ProductsController@delete')->name('delete');
