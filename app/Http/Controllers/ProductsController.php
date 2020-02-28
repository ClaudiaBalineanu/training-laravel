<?php


namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProductsController extends Controller
{
    /**
     * Render resources, a list of products
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function index()
    {
        $cart = request()->session()->get('cart.products', []);

        if ($cart) {
            $products = Product::query()->whereNotIn('id', $cart)->get();
        } else {
            $products = Product::query()->get();
        }

        if (request()->ajax()) {
            return $products;
        }

        return view('products.index', compact('products'));
    }

    /**
     * Add a product to cart
     *
     * @param Product $product
     * @return array
     */
    public function addToCart(Product $product)
    {
        $cart = request()->session()->get('cart.products', []);

        // check if the id product is already in the cart
        if (! in_array($product->getKey(), $cart)) {
            array_push($cart, $product->getKey());

            request()->session()->put('cart.products', $cart);
        }

        if (request()->ajax()) {
            return ['success' => true];
        }

        return redirect()->route('home');
    }

    /**
     * Show all products from database
     *
     * @return Product[]|\Illuminate\Database\Eloquent\Collection
     */
    public function show()
    {
        $products = Product::all();

        if (request()->ajax()) {
            return $products;
        }

        return view('products.products', compact('products'));
    }

    /**
     * Return the view to create a product.
     *
     * @return array
     */
    public function create()
    {
        return view('products.save');
    }

    /**
     * Persist data of the new product in database
     *
     * @return array
     */
    public function store()
    {
        $validateAttributes = $this->validateProduct();

        // make the name of the image unique
        $imageName = uniqid() . '.' . request()->image->getClientOriginalExtension();

        // save the image in the folder
        request()->image->move(public_path('images'), $imageName);

        $validateAttributes['image'] = $imageName;

        $product = new Product($validateAttributes);

        $product->save();

        if (request()->ajax()) {
            return ['success' => true];
        }

        return redirect()->route('create');
    }

    /**
     * Return the view to edit a product
     *
     * @param Product $product
     * @return Product
     */
    public function edit(Product $product)
    {
        if (request()->ajax()) {
            return $product;
        }

        return view('products.save', compact('product'));
    }

    /**
     * Persist (update) the edited product
     *
     * @param Product $product
     * @return array
     */
    public function update(Product $product)
    {
        if (request()->hasFile('image')) {

            // save the name af the old image
            $oldImage = $product->getImagePath();

            $validateAttributes = $this->validateProduct();

            // make the name of the image unique
            $imageName = uniqid() . '.' . request()->image->getClientOriginalExtension();

            // save the image in the folder
            request()->image->move(public_path('images'), $imageName);

            $validateAttributes['image'] = $imageName;

            $product->update($validateAttributes);

            // delete the old image from sever
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }

        } else {

            $validateAttributes = request()->validate([
                'title' => 'required|min:3',
                'description' => 'required',
                'price' => 'required|numeric'
            ]);

            $validateAttributes['image'] = $product->image;

            $product->update($validateAttributes);
        }

        if (request()->ajax()) {
            return ['success' => true];
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
        return request()->validate([
            'title' => 'required|min:3',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image'
        ]);
    }

    /**
     * Delete a product
     *
     * @param Product $product
     * @return array
     * @throws \Exception
     */
    public function delete(Product $product)
    {
        $product->delete();

        $oldImage = $product->getImagePath();

        // delete the image form server
        if(File::exists($oldImage)) {
            File::delete($oldImage);
        }

        if (request()->ajax()) {
            return ['success' => $oldImage];
        }

        return redirect()->route('products');
    }

}
