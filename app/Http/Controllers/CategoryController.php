<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Category;
        $product-> name = $request->name;
        $product-> total_product = $request->total_product;
        $product->save();

        return response()->json([
            "message" => "Category Added."
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!empty($category)) {
            return response()->json($category);
        }
        else {
            return response()->json([
                "message" => "Category not found."
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'total_product' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $category = Category::find($id);

            if ($category) {
                $category->update([
                    'name' => $request->name,
                    'total_product' => $request->total_product,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Category updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Category with ID $id not found."
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->delete();

            return response()->json([
                "message" => "Category deleted."
            ], 202);
        }
        else {
            return response()->json([
                "message" => "Category not found."
            ], 404);
        }
    }
}
