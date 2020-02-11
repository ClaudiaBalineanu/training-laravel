<?php


namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
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

    public function show()
    {
        // show all products from database
        return view('products', [
            'products' => Product::all()
        ]);
    }

    public function create()
    {
        // return view to create a product
        return view('create');
    }

    public function store(Request $request)
    {
        // persist data of the new product in database
        Product::create($this->validateProduct($request));

        return redirect('/products/create');
    }

    public function edit(Product $product)
    {
        // return view to edit a product
        return view('edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // persist the edited product

        // save the name af the old image
        $img = $product->image;

        $product->update($this->validateProduct($request));

        // delete the old image from sever
        if (isset($img) && File::exists(public_path('images') . '\\' . $img)) {
            File::delete(public_path('images') . '\\' . $img);
        }

        return redirect('/products');
    }

    public function validateProduct(Request $request)
    {
        // validate the data inserted by the user in the view form
        $validateAttributes = $request->validate([
            'title' => 'required|min:3|regex:/^[a-zA-Z ]*$/',
            'description' => 'required',
            'price' => 'required|regex:/^[0-9]*\.[0-9]+$/',
            'image' => 'required|image'
        ]);

        // make the name of the image unique
        $imageName = uniqid() . '.' . $request->image->getClientOriginalExtension();

        // save the image in the folder
        $request->image->move(public_path('images'), $imageName);

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
