<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    protected function getStatusValues()
    {
        return ['pending', 'processing', 'completed', 'canceled'];
    }

    protected function getPaymentMethodValues()
    {
        return ['qris', 'cash', 'transfer', 'debit', 'credit'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required',
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:' . implode(',', $this->getStatusValues())],
            'grand_total' => 'required',
            'item_count' => 'required',
            'is_paid' => 'required|boolean',
            'payment_method' => ['required', 'in:' . implode(',', $this->getPaymentMethodValues())]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $order = new Order;
            $order->order_code = $request->order_code;
            $order->user_id = $request->user_id;
            $order->status = $request->status;
            $order->grand_total = $request->grand_total;
            $order->item_count = $request->item_count;
            $order->is_paid = $request->is_paid;
            $order->payment_method = $request->payment_method;
            $order->save();

            return response()->json([
                "message" => "Order Added."
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        if (!empty($order)) {
            return response()->json($order);
        } else {
            return response()->json([
                "message" => "Order not found."
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
            'order_code' => 'required',
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:' . implode(',', $this->getStatusValues())],
            'grand_total' => 'required',
            'item_count' => 'required',
            'is_paid' => 'required|boolean',
            'payment_method' => ['required', 'in:' . implode(',', $this->getPaymentMethodValues())]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $order = Order::find($id);

            if ($order) {
                $order->update([
                    'order_code' => $request->order_code,
                    'user_id' => $request->user_id,
                    'status' => $request->status,
                    'grand_total' => $request->grand_total,
                    'item_count' => $request->item_count,
                    'is_paid' => $request->is_paid,
                    'payment_method' => $request->payment_method
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Order updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Order with ID $id not found."
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Order::where('id', $id)->exists()) {
            $order = Order::find($id);
            $order->delete();

            return response()->json([
                "message" => "Order deleted."
            ], 202);
        } else {
            return response()->json([
                "message" => "Order not found."
            ], 404);
        }
    }
}
