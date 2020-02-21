<?php

namespace App\Http\Controllers;

use App\Order;

class OrdersController extends Controller
{
    /**
     * Display all orders.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::with('value')->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display an order with specific products.
     *
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        return view('orders.order', compact('order'));
    }

}
