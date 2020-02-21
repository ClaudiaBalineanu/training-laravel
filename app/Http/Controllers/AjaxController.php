<?php


namespace App\Http\Controllers;


use App\Mail\Checkout;
use App\Order;
use App\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class AjaxController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cart = request()->session()->get('cart.products');

        if ($cart) {
            $products = Product::query()->whereNotIn('id', $cart)->get();
        } else {
            $products = Product::query()->get();
        }

        return response()->json($products);
    }

    public function addToCart(Product $product)
    {
        $cart = request()->session()->get('cart.products', []);

        // check if the id product is already in the cart
        if (! in_array($product->getKey(), $cart)) {
            array_push($cart, $product->getKey());

            $cart = request()->session()->put('cart.products', $cart);
        }

        return response()->json(['product' => $cart]);  //redirect()->route('view');
    }

    public function cart()
    {
        $cart = request()->session()->get('cart.products', []);

        $products = Product::query()->whereIn('id', $cart)->get();

        return response()->json($products);
    }

    public function remove(Product $product)
    {
        $cart = request()->session()->get('cart.products');

        if ($product->getKey()) {

            $keySession = array_search($product->getKey(), $cart);

            if (isset($cart[$keySession])) {
                // unset the id for product from session
                unset($cart[$keySession]);

                $cart = request()->session()->put('cart.products', $cart);
            }
        }

        return response()->json(['product' => $cart]);  //redirect()->back();
    }

    public function checkout()
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

            //return redirect()->route('cart')->with('message', 'Email sent');
            return response()->json(['message' => 'Email sent']);

        } else {

            //return redirect()->route('checkout')->with('message', 'Cart empty');
            return response()->json(['message' => 'Cart empty']);
        }
    }

    public function validateCheckout()
    {
        return request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
        ]);
    }

    public function show()
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function delete(Product $product)
    {
        $img = $product->image;

        $product->query()->delete();

        // delete the image form server
        if(isset($img) && File::exists(public_path('images') . '\\' . $img)) {
            //File::delete(public_path('images') . '\\' . $img);
        }

        //dd($product);




        return response()->json(['id' => 'delete']);
    }
}
