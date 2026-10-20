<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartPageController extends Controller
{
    public function index(Request $request): View
    {
        $cartItems = CartItem::with([
            'menuItem.restaurant',
        ])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return (float) $item->menuItem->price * $item->quantity;
        });

        return view('customer.cart.index', compact(
            'cartItems',
            'subtotal'
        ));
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless(
            $cartItem->user_id === $request->user()->id,
            403
        );

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cartItem->update($validated);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless(
            $cartItem->user_id === $request->user()->id,
            403
        );

        $cartItem->delete();

        return back()->with('success', 'Item removed from your bag.');
    }
}
