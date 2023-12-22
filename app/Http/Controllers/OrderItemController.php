<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Validator;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order_items = OrderItem::all();
        return response()->json($order_items);
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
            'order_id' => ['required', 'exists:orders,id'],
            'product_id' => ['required', 'exists:products,id'],
            // 'length' => 'required',
            // 'width' => 'required',
            // 'height' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $order_item = new OrderItem;
            $order_item->order_id = $request->order_id;
            $order_item->product_id = $request->product_id;
            $order_item->length = $request->length;
            $order_item->width = $request->width;
            $order_item->height = $request->height;
            $order_item->price = $request->price;
            $order_item->quantity = $request->quantity;
            $order_item->save();

            return response()->json([
                "message" => "Order Item Added."
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order_item = OrderItem::find($id);
        if (!empty($order_item)) {
            return response()->json($order_item);
        } else {
            return response()->json([
                "message" => "Order Item not found."
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
            'order_id' => ['required', 'exists:orders,id'],
            'product_id' => ['required', 'exists:products,id'],
            // 'length' => 'required',
            // 'width' => 'required',
            // 'height' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $order_item = OrderItem::find($id);

            if ($order_item) {
                $order_item->update([
                    'order_id' => $request->order_id,
                    'product_id' => $request->product_id,
                    'length' => $request->length,
                    'width' => $request->width,
                    'height' => $request->height,
                    'price' => $request->price,
                    'quantity' => $request->quantity
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Order Item updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Order Item with ID $id not found."
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (OrderItem::where('id', $id)->exists()) {
            $order_item = OrderItem::find($id);
            $order_item->delete();

            return response()->json([
                "message" => "Order Item deleted."
            ], 202);
        } else {
            return response()->json([
                "message" => "Order Item not found."
            ], 404);
        }
    }
}
