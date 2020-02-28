<?php

namespace App\Http\Controllers;

use App\Order;

class OrdersController extends Controller
{
    /**
     * Display all orders.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $orders = Order::with('value')->get();

        if (request()->ajax()) {
            return $orders;
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Display an order with specific products.
     *
     * @param Order $order
     * @return Order
     */
    public function show(Order $order)
    {
        $order['value'] = $order->value()->first();

        if (request()->ajax()) {
            return response()->json(['order' => $order, 'products' => $order->products]);
        }

        return view('orders.order', compact('order'));
    }

}
