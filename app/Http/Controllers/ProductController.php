<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image_path' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'type' => ['required', 'exists:categories,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $product = new Product;
            $product->name = $request->name;
            $product->image_path = $request->image_path;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->type = $request->type;
            $product->save();

            return response()->json([
                "message" => "Product Added."
            ], 201);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!empty($product)) {
            return response()->json($product);
        } else {
            return response()->json([
                "message" => "Product not found."
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image_path' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'type' => ['required', 'exists:categories,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $product = Product::find($id);

            if ($product) {
                $product->update([
                    'name' => $request->name,
                    'image_path' => $request->image_path,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'type' => $request->type
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Product updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Product with ID $id not found."
                ], 404);
            }
        }
    }

    public function destroy($id)
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $product->delete();

            return response()->json([
                "message" => "Product deleted."
            ], 202);
        } else {
            return response()->json([
                "message" => "Product not found."
            ], 404);
        }
    }
}
