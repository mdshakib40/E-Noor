<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $menuItem = MenuItem::visible()
            ->findOrFail($validated['menu_item_id']);

        $quantity = $validated['quantity'] ?? 1;

        $cartItem = CartItem::where('user_id', $request->user()->id)
            ->where('menu_item_id', $menuItem->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = min(99, $cartItem->quantity + $quantity);
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $request->user()->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $quantity,
            ]);
        }

        $cartCount = CartItem::where('user_id', $request->user()->id)
            ->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Added to your bag.',
            'cart_count' => $cartCount,
        ]);
    }
}
