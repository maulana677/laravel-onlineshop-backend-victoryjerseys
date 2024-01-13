<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        return view('pages.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'name'     => 'required|min:3',
            'description'   => 'nullable|min:3',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required'
        ]);

        //upload image
        $filename = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);

        //create post
        Product::create([
            'image' => $filename,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id
        ]);

        toast('Product created successfully', 'success')->width('400');

        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = Category::all();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,jpg,png|max:2048',
            'name'     => 'nullable|min:3',
            'description'   => 'nullable|min:3',
            'price' => 'nullable|integer',
            'stock' => 'nullable|integer',
            'category_id' => 'nullable'
        ]);

        $product = Product::find($id);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //delete old image
            Storage::delete('public/products/' . $product->image);

            //update post
            Product::find($id)->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id
            ]);
        }

        toast('Product updated successfully', 'success')->width('400');

        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            Storage::delete('public/products/' . $product->image);
            $product->delete();
            return response(['status' => 'success', 'message' => 'User successfully deleted']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => 'There is something wrong!']);
        }
    }
}
