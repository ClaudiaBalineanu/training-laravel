<?php


namespace App\Http\Controllers;

use App\Mail\Checkout;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        // show products in cart and the form for checkout
        $cart = $request->session()->has('cart.products') ? $request->session()->get('cart.products') : [];

        $products = Product::whereIn('id', $cart)->get();

        return view('checkout', compact('products'));
    }

    public function removeFromCart(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart.products');

        if ($product->id) {

            $keySession = array_search($product->id, $cart);

            if (isset($cart[$keySession])) {

                //unset the id for product from session
                unset($cart[$keySession]);
                $request->session()->put('cart.products', $cart);
            }
        }
        return redirect()->route('checkout');
    }

    public function checkoutCart(Request $request)
    {
        // persist data from form, send email
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $cart = $request->session()->has('cart.products') ? $request->session()->get('cart.products') : [];
        $products = Product::whereIn('id', $cart)->get();

        if (! empty($cart)) {

            Mail::to(request('email'))->send(new Checkout($products));

            // clean session
            $request->session()->forget('cart.products');

            return redirect()->route('cart')->with('message', 'Email sent');

        } else {

            return redirect()->route('checkout')->with('message', 'Cart empty');
        }
    }
}
