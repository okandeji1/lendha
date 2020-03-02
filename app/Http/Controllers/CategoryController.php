<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Auth;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($categories);
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
        // Validate form data
        $this->validate($request, [
            'name' => 'required'
        ]);

        // Form data
        $data = $request->only(['name']);
        // Check if user already exit
        if(Category::where('name', '=', $data['name'])->exists()){
            $response = array(
                "status" => "error",
                "message" => "This category already exist",
            );
            return response()->json($response);
        }
        // Store Vendor
        $category = new Category();
        $category->uuid = Uuid::uuid4();
        $category->name = $data['name'];
        $category->save();
        
        $response = array(
            "status" => "success",
            "message" => "Category added successfully",
        );
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $deletecategory = Category::find($id);
        // Check for correct user
        if($deletecategory){
            $deletecategory ->delete();
            return back()->with('success', 'Category was deleted successfuly');
        }else {
            return back()->with('success', 'Unable to delete category');
        }
    }
}
