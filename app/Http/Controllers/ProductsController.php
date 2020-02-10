<?php


namespace App\Http\Controllers;


use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Checkout;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // render resources, a list of products
        $cart = $request->session()->get('cart.products');

        if ($cart) {
            $products = Product::whereNotIn('id', $cart)->get();
        } else {
            $products = Product::get();
        }

        return view('index', compact('products'));
    }

    public function addToCart(Request $request, Product $product)
    {
        // add to cart product
        $sessionCart = $request->session()->get('cart.products');

        if ($sessionCart) {
            array_push($sessionCart, $product->id);
            $request->session()->put('cart.products', $sessionCart);
        } else {
            $request->session()->push('cart.products', $product->id);
        }

        return redirect()->route('index');
    }

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

    public function create()
    {
        // return view to create a product
        return view('create');
    }

    public function store()
    {
        // persist data of the new product in database
        Product::create($this->validateProduct());

        return redirect('/products/create');
    }

    public function edit(Product $product)
    {
        // return view to edit a product
        return view('edit', compact('product'));
    }

    public function update(Product $product)
    {
        // persist the edited product

        $img = $product->image;

        $product->update($this->validateProduct());

        if(isset($img) && File::exists(public_path('images') . '\\' . $img)) {
            File::delete(public_path('images') . '\\' . $img);
        }

        return redirect('/products');
        //return redirect('/products/' . $product->id . '/edit');
    }

    public function validateProduct()
    {
        // validate the data inserted by the user in the view form
        $validateAttributes = request()->validate([
            'title' => ['required', 'min:3'],
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|image'
        ]);

        // make the name of the image unique
        $imageName = uniqid().'.'.request()->image->getClientOriginalExtension();

        // save the image in the folder
        request()->image->move(public_path('images'), $imageName);

        $validateAttributes['image'] = $imageName;

        return $validateAttributes;
    }

    public function delete(Product $product)
    {
        $img = $product->image;

        $product->delete();

        if(isset($img) && File::exists(public_path('images') . '\\' . $img)) {
            File::delete(public_path('images') . '\\' . $img);
        }

        return redirect('/products');
    }

}
