<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // passing category & product to the view
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
            return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|file|mimes:jpg,png,peg,svg,gif,jpeg',
            'category' => 'required',
            'price' => 'required',
            'dsicription' => 'required',
        ]);
        $product = new Product();
        // Handle file upload
        if ($request->hasFile('image')) {
            $path = request()->file('image')->store('products');
            $product->image = $path;
        }else {
            $response = array(
                "status" => "error",
                "message" => "Image is required",
            );
            return response()->json($response);
        }
        // Form data
        $data = $request->only(['discription', 'price', 'category']);
        // Get category
        $category = Category::where('name', '=', $data['category'])->firstOrFail();
        $category_id = $category->id;
        // Create Product
        $product->uuid = Uuid::uuid4();
        $product->category_id = $category_id;
        $product->price = $data['price'];
        $product->discription = $data['discription'];
        $product->save();

        $response = array(
            "status" => "success",
            "message" => "Product successfully created",
        );
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product = Product::where('uuid',$uuid)->first();
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this -> validate($request, [
            'price' => 'required',
            'discription' => 'required',
            'category' => 'required',
            'image' => 'file|mimes:jpg,png,peg,svg,gif,jpeg'
        ]);
        // Handle file upload
        if ($request->hasFile('image')) {
            $path = request()->file('image')->store('products');
        }
        $price = $request->price;
        $discription = $request->discription;
        $category = $request->category;
        $productId = $request->id;
        // Update Product
        $updateProduct = Product::find($productId);
        if($updateProduct){
            $updateProduct->header = $header;
            $updateProduct->content = $content;
            if($category !== null){
                $getCategory = Category::where('name', '=', $category)->firstOrFail();
                $getCategory_id = $getCategory->id;
                $updateProduct->category_id = $getCategory_id;
                $updateProduct->save();

                $response = array(
                    "status" => "success",
                    "message" => "Product successfully updated",
                );
                return response()->json($response);
            }else {
                $response = array(
                    "status" => "success",
                    "message" => "Product successfully updated",
                );
                $updateProduct->save();
                return response()->json($response);
            }
        }else {
            $response = array(
                "status" => "error",
                "message" => "Product cannot be updated",
            );
            return response()->json($response);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $productId = $request->id;
        $deleteProduct = Post::find($productId);
        // Check for correct user
        if($deleteProduct){
            // Delete Image
            Storage::delete('product/'. $deleteProduct->image);
            $deleteProduct ->delete();
            $response = array(
                "status" => "success",
                "message" => "Product Successfully deleted",
            );
            return response()->json($response);
        }else {
            $response = array(
                "status" => "error",
                "message" => "Unable to delete product",
            );
            return response()->json($response);
        }
    }
}
