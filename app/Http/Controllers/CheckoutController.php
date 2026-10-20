<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    private const ZONES = [
        'inside_dhaka' => 60,
        'outside_dhaka' => 120,
    ];

    public function index()
    {
        $cartItems = CartItem::with('menuItem.restaurant')
            ->where('user_id', auth()->id())
            ->get();

        abort_if($cartItems->isEmpty(),  redirect()->route('cart.index'));

        $items = $cartItems->map(function ($cartItem) {
            $menuItem = $cartItem->menuItem;

            return [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'restaurant' => $menuItem->restaurant->name,
                'image' => $menuItem->image
                    ? asset('storage/' . $menuItem->image)
                    : null,
                'qty' => $cartItem->quantity,
                'price' => (float) $menuItem->price,
            ];
        });

        $subtotal = $items->sum(
            fn ($item) => $item['qty'] * $item['price']
        );

        $zones = [
            'inside_dhaka' => [
                'label' => 'Inside Dhaka',
                'fee' => self::ZONES['inside_dhaka'],
            ],
            'outside_dhaka' => [
                'label' => 'Outside Dhaka',
                'fee' => self::ZONES['outside_dhaka'],
            ],
        ];

        $zone = old('delivery_zone', 'inside_dhaka');
        $deliveryFee = self::ZONES[$zone] ?? self::ZONES['inside_dhaka'];
        $total = $subtotal + $deliveryFee;

        $money = fn ($amount) => '৳' . number_format($amount, 2);

        $cartRoute = 'cart.index';

        return view('checkout.index', compact(
            'items',
            'subtotal',
            'zones',
            'zone',
            'deliveryFee',
            'total',
            'money',
            'cartRoute'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'delivery_zone' => [
                'required',
                Rule::in(array_keys(self::ZONES)),
            ],
            'address' => ['required', 'string', 'max:2000'],
            'instructions' => ['nullable', 'string', 'max:300'],
            'payment_method' => [
    'required',
    Rule::in(['cash_on_delivery']),
],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $cartItems = CartItem::with('menuItem.restaurant')
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $restaurantIds = $cartItems
                ->pluck('menuItem.restaurant_id')
                ->unique();

            if ($restaurantIds->count() !== 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cart' => 'Please order from one restaurant at a time.',
                ]);
            }

            $subtotal = 0;

            foreach ($cartItems as $cartItem) {
                $menuItem = $cartItem->menuItem;

                if (
                    ! $menuItem ||
                    ! $menuItem->is_available ||
                    $menuItem->status !== 'active' ||
                    $menuItem->restaurant->status !== 'active'
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cart' => 'An item in your cart is no longer available. Please review your cart.',
                    ]);
                }

                $subtotal += (float) $menuItem->price * $cartItem->quantity;
            }

            $deliveryFee = self::ZONES[$validated['delivery_zone']];
            $total = $subtotal + $deliveryFee;

            $order = Order::create([
                'customer_id' => auth()->id(),
                'restaurant_id' => $restaurantIds->first(),
                'order_number' => 'NOOR-' . strtoupper(Str::random(10)),
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount' => 0,
                'total' => $total,
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'delivery_address' => $validated['address'],
                'delivery_zone' => $validated['delivery_zone'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['instructions'] ?? null,
            ]);

            foreach ($cartItems as $cartItem) {
                $menuItem = $cartItem->menuItem;
                $unitPrice = (float) $menuItem->price;

                $order->items()->create([
                    'product_name' => $menuItem->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $cartItem->quantity,
                    'total' => $unitPrice * $cartItem->quantity,
                ]);
            }

            CartItem::where('user_id', auth()->id())->delete();

            return $order;
        });

        if ($request->expectsJson()) {
    return response()->json([
        'success' => true,
        'order_number' => $order->order_number,
        'total' => $order->total,
    ]);
}

if ($request->expectsJson()) {
    return response()->json([
        'success' => true,
        'order_number' => $order->order_number,
        'total' => $order->total,
        'redirect' => route('dashboard'),
    ]);
}
    }
}
