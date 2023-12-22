<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::all();
        return response()->json($shops);
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
        $validator = Validator::make($request->all(), [
            'shop_name' => 'required',
            'shop_code' => 'required',
            'shop_address' => 'required',
            'logo_path' => 'required',
            'owner_id' => ['required', 'exists:users,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $shop = new Shop;
            $shop->shop_name = $request->shop_name;
            $shop->shop_code = $request->shop_code;
            $shop->shop_address = $request->shop_address;
            $shop->logo_path = $request->logo_path;
            $shop->owner_id = $request->owner_id;
            $shop->save();

            return response()->json([
                "message" => "Shop Added."
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = Shop::find($id);
        if (!empty($shop)) {
            return response()->json($shop);
        } else {
            return response()->json([
                "message" => "Shop not found."
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
            'shop_name' => 'required',
            'shop_code' => 'required',
            'shop_address' => 'required',
            'logo_path' => 'required',
            'owner_id' => ['required', 'exists:users,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $shop = Shop::find($id);

            if ($shop) {
                $shop->update([
                    'shop_name' => $request->shop_name,
                    'shop_code' => $request->shop_code,
                    'shop_address' => $request->shop_address,
                    'logo_path' => $request->logo_path,
                    'owner_id' => $request->owner_id
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Shop updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Shop with ID $id not found."
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Shop::where('id', $id)->exists()) {
            $shop = Shop::find($id);
            $shop->delete();

            return response()->json([
                "message" => "Shop deleted."
            ], 202);
        } else {
            return response()->json([
                "message" => "Shop not found."
            ], 404);
        }
    }
}
