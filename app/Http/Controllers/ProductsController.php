<?php


namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    /**
     * Render resources, a list of products
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cart = request()->session()->get('cart.products');

        if ($cart) {
            $products = Product::query()->whereNotIn('id', $cart)->get();
        } else {
            $products = Product::query()->get();
        }

        return view('index', compact('products'));
    }

    /**
     * Add to cart product
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Product $product)
    {
        $cart = request()->session()->get('cart.products', []);

        // check if the id product is already in the cart
        if (! in_array($product->getKey(), $cart)) {
            array_push($cart, $product->getKey());

            request()->session()->put('cart.products', $cart);
        }

        return redirect()->route('index');
    }

    /**
     * Show all products from database
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('products', [
            'products' => Product::all()
        ]);
    }

    /**
     * Return the view to create a product
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('crud');
    }

    /**
     * Persist data of the new product in database
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $product = new Product($this->validateProduct());

        $product->save();

        return redirect()->route('create');
    }

    /**
     * Return the view to edit a product
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('crud', compact('product'));
    }

    /**
     * Persist (update) the edited product
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Product $product)
    {
        // save the name af the old image
        $img = $product->image;

        $product->update($this->validateProduct());

        //$product->save();

        // delete the old image from sever
        if (isset($img) && File::exists(public_path('images') . '\\' . $img)) {
            File::delete(public_path('images') . '\\' . $img);
        }

        return redirect()->route('products');
    }

    /**
     * Validate the data inserted by the user in the view form
     *
     * @return array
     */
    public function validateProduct()
    {
        $validateAttributes = request()->validate([
            'title' => 'required|min:3|regex:/^[a-zA-Z ]*$/',
            'description' => 'required',
            'price' => 'required|regex:/^[0-9]*\.[0-9]+$/',
            'image' => 'required|image'
        ]);

        // make the name of the image unique
        $imageName = uniqid() . '.' . request()->image->getClientOriginalExtension();

        // save the image in the folder
        request()->image->move(public_path('images'), $imageName);

        $validateAttributes['image'] = $imageName;

        return $validateAttributes;
    }

    /**
     * Delete a product
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Product $product)
    {
        $img = $product->image;

        $product->delete();

        // delete the image form server
        if(isset($img) && File::exists(public_path('images') . '\\' . $img)) {
            File::delete(public_path('images') . '\\' . $img);
        }

        return redirect()->route('products');
    }

}
