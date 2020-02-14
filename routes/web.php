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

// index and add to cart
Route::get('/index', 'ProductsController@index')->name('index');
Route::get('/add-to-cart/{product}', 'ProductsController@addToCart')->name('addToCart.product');

// cart and remove from cart
Route::get('/cart', 'CartController@cart')->name('cart');
Route::get('/remove-from-cart/{product}', 'CartController@removeFromCart')->name('removeFromCart.product');

// checkout (cart page) send email
Route::post('/cart', 'CartController@checkoutCart')->name('checkout');

// for login, authenticate
Auth::routes();

// show all products
Route::get('/products', 'ProductsController@show')->name('products')->middleware('auth');

// create and persist a product
Route::post('/products', 'ProductsController@store')->name('store');
Route::get('/products/create', 'ProductsController@create')->name('create')->middleware('auth');

// edit and persist a product
Route::put('/products/{product}', 'ProductsController@update')->name('update')->middleware('auth');
Route::get('/products/edit/{product}', 'ProductsController@edit')->name('edit')->middleware('auth');

// delete a product
Route::get('/products/delete/{product}', 'ProductsController@delete')->name('delete')->middleware('auth');

// orders
Route::get('/orders', 'OrdersController@index')->name('orders')->middleware('auth');
Route::get('/orders/{order}', 'OrdersController@show')->name('order')->middleware('auth');

