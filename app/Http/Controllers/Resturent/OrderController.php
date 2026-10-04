<?php

namespace App\Http\Controllers\Resturent;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->with(['customer', 'items'])
            ->latest()
            ->get();

        return view('resturent.orders.index', compact('orders', 'restaurant'));
    }

    public function show(Order $order)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['customer', 'items']);

        return view('resturent.orders.show', compact('order', 'restaurant'));
    }
}