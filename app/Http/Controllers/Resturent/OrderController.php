<?php

namespace App\Http\Controllers\Resturent;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (! $restaurant) {
            abort(404, 'Restaurant not found.');
        }

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->with(['customer', 'items'])
            ->latest()
            ->get();

        return view('resturent.orders.index', compact(
            'orders',
            'restaurant'
        ));
    }

    public function show(Order $order)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (! $restaurant) {
            abort(404, 'Restaurant not found.');
        }

        if ((int) $order->restaurant_id !== (int) $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['customer', 'items']);

        return view('resturent.orders.show', compact(
            'order',
            'restaurant'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (! $restaurant) {
            abort(404, 'Restaurant not found.');
        }

        if ((int) $order->restaurant_id !== (int) $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'status' => [
                'required',
                'in:accepted,rejected,preparing,ready_for_pickup',
            ],
        ]);

        $allowedTransitions = [
            'pending' => ['accepted', 'rejected'],

            // Online payment success sets order status to confirmed.
            'confirmed' => ['accepted', 'rejected'],

            'accepted' => ['preparing', 'rejected'],
            'preparing' => ['ready_for_pickup'],
        ];

        $currentStatus = $order->status;
        $newStatus = $validated['status'];

        if (! in_array(
            $newStatus,
            $allowedTransitions[$currentStatus] ?? [],
            true
        )) {
            throw ValidationException::withMessages([
                'status' => 'This order status transition is not allowed.',
            ]);
        }

        $order->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('resturent.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
