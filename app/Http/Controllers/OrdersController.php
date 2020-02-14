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
        $orders = Order::with('products')->get();

        foreach ($orders as $order) {
            $this->totals($order);
        }

        return view('orders', compact('orders'));
    }

    /**
     * Display an order with specific products.
     *
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        $this->totals($order);

        return view('order', compact('order'));
    }

    /**
     * The total per order, the summed prices of products per order
     *
     * @param Order $order
     * @return mixed
     */
    public function totals(Order $order)
    {
        return $order['total'] = $order->products->sum('price');
    }

}
