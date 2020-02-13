<?php

namespace App\Http\Controllers;

use App\Mail\Checkout;
use App\Order;
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

        return view('checkout', compact('products'));
    }

    /**
     * Remove an item (product) from cart
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromCart(Product $product)
    {
        $cart = request()->session()->get('cart.products');

        if ($product->getKey()) {

            $keySession = array_search($product->getKey(), $cart);

            if (isset($cart[$keySession])) {
                // unset the id for product from session
                unset($cart[$keySession]);

                request()->session()->put('cart.products', $cart);
            }
        }

        return redirect()->route('checkout');
    }

    /**
     * Persist data from form, send email
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkoutCart()
    {
        $this->validateCheckout();

        $cart = request()->session()->get('cart.products', []);

        $products = Product::query()->whereIn('id', $cart)->get();

        if (! empty($cart)) {

            $order = new Order($this->validateCheckout());
            $order->save();

            foreach ($cart as $id) {
                $orderProduct = new OrderProduct(['order_id' => $order->getKey(), 'product_id' => $id]);
                $orderProduct->save();
            }

            // send email
            Mail::to(request('email'))->send(new Checkout($products));

            // clean session
            request()->session()->forget('cart.products');

            return redirect()->route('cart')->with('message', 'Email sent');

        } else {

            return redirect()->route('checkout')->with('message', 'Cart empty');
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
