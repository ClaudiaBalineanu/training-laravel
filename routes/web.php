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

// tests route
Route::get('/test', function(){
//    abort(404);

    //$r = \App\Order::with('value')->get();

    //dump($r->toArray());
    $order = \App\Order::find(2);
    $r = $order->products()->attach(5);
    //$r->save;
    return $r;

    //return \App\Product::with('orders')->sum('price');
    //$orders = \App\Order::with('products')->get();
    //foreach ($orders as $order) {
    //    $order['total'] = $order->products->sum('price');
    //}
    //$r = ''; //route('create');  // Storage::get('file.jpg')
    //return asset('images/' . '1.jpg'); //Storage::get('1.jpg');  //url('/images/') . '1.jpg';  // request()->url();  //$order->products; // url('/')
    //return $orders;
    //return \App\Product::all();


    //$cart = request()->session()->get('cart.products', []);

    //$products = App\Product::query()->whereIn('id', $cart)->get();
    //return $products;

    //return config('database.path.image');
    //return basename(public_path('images') . '\\' . '1.jpg');
});

// index and add to cart
Route::get('/', 'ProductsController@index')->name('home');


Route::get('/spa', function () {
    return view('spa');
})->name('spa');

// cart and remove from cart
Route::get('/cart', 'CartController@cart')->name('cart');
Route::get('/cart/add/{product}', 'ProductsController@addToCart')->name('cart.add');
Route::get('/cart/remove/{product}', 'CartController@remove')->name('cart.remove');

// checkout (cart page) send email
Route::post('/cart', 'CartController@checkout')->name('checkout');

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







Route::get('/users', 'UsersController@index')->name('users')->middleware('auth');

Route::get('/users/reset/{user}', 'UsersController@sendResetLinkEmail')->name('reset')->middleware('auth');

Route::get('/users/disable/{user}', 'UsersController@disableUser')->name('disable')->middleware('auth');
Route::get('/users/enable/{user}', 'UsersController@enableUser')->name('enable')->middleware('auth');









/*

Route::get('/', function () {
    return view('test');
})->name('view');

Route::get('/index', 'AjaxController@index')->name('index');
Route::get('/addToCart/{product}', 'AjaxController@addToCart')->name('addToCart');

Route::get('/cart', 'AjaxController@cart')->name('cart');
Route::get('/remove/{product}', 'AjaxController@remove')->name('remove');

Route::post('/cart', 'AjaxController@checkout')->name('checkout');

Route::get('/products', 'AjaxController@show')->name('products');

Route::get('/delete/{product}', 'AjaxController@delete')->name('delete');
*/
