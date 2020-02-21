<?php

namespace App\Http\Controllers;

use App\Mail\Checkout;
use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    /**
     * Show products in cart and the form for checkout
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart()
    {
        $cart = request()->session()->get('cart.products', []);

        $products = Product::query()->whereIn('id', $cart)->get();

        if (request()->ajax()) {
            return $products;
        }

        return view('cart.index', compact('products'));
    }

    /**
     * Remove product from cart
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Product $product)
    {
        $cart = request()->session()->get('cart.products');

        $keySession = array_search($product->getKey(), $cart);

        if (isset($cart[$keySession])) {
            // unset the id for product from session
            unset($cart[$keySession]);

            request()->session()->put('cart.products', $cart);
        }

        return redirect()->route('cart');
    }

    /**
     * Persist data from form, send email
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout()
    {
        $this->validateCheckout();

        $cart = request()->session()->get('cart.products', []);

        if (! empty($cart)) {

            $order = new Order($this->validateCheckout());
            $order->save();

            foreach ($cart as $id) {
                $order->products()->attach($id);
            }

            // clean session
            request()->session()->forget('cart.products');

            // send email
            Mail::to(request('email'))->send(new Checkout($order));

            return redirect()->route('cart')->with('message', 'Email sent');

        } else {
            return redirect()->route('cart')->with('message', 'Cart empty');
        }
    }

    /**
     * Validate attributes for checkout
     *
     * @return array
     */
    public function validateCheckout()
    {
        return request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
        ]);
    }
}
