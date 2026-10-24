<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\RiderLocation;
use App\Models\RestaurantSettlement;

class RiderController extends Controller
{
    private const READY = 'ready_for_pickup';
    private const PICKED_UP = 'picked_up';
    private const OUT_FOR_DELIVERY = 'out_for_delivery';
    private const DELIVERED = 'delivered';

    private const TRANSITIONS = [
        'pickup' => [
            self::READY,
            self::PICKED_UP,
            'picked_up_at',
            'Pickup confirmed.',
        ],
        'out' => [
            self::PICKED_UP,
            self::OUT_FOR_DELIVERY,
            null,
            'Order is out for delivery.',
        ],
        'delivered' => [
            self::OUT_FOR_DELIVERY,
            self::DELIVERED,
            'delivered_at',
            'Order marked as delivered. Great work!',
        ],
    ];

    private const WITH = ['restaurant', 'customer', 'items'];

    public function index(Request $request)
    {
        $this->ensureReady();

        $rider = $request->user();

        $stats = [
            'available' => $this->availableQuery()->count(),
            'active' => $this->activeQuery($rider)->count(),
            'completed_today' => $this->mine($rider)
                ->where('status', self::DELIVERED)
                ->whereDate('delivered_at', today())
                ->count(),
            'earnings_today' => $this->earningsToday($rider),
        ];

        $active = $this->activeQuery($rider)
            ->with(self::WITH)
            ->oldest('rider_accepted_at')
            ->get()
            ->map(fn ($order) => $this->present($order));

        $available = $this->availableQuery()
            ->with(self::WITH)
            ->oldest()
            ->limit(5)
            ->get()
            ->map(fn ($order) => $this->present($order));

        $recent = $this->mine($rider)
            ->where('status', self::DELIVERED)
            ->with(self::WITH)
            ->latest('delivered_at')
            ->limit(5)
            ->get()
            ->map(fn ($order) => $this->present($order));

        return view('rider.overview', compact(
            'stats',
            'active',
            'available',
            'recent'
        ));
    }

    public function available()
    {
        $this->ensureReady();

        $orders = $this->availableQuery()
            ->with(self::WITH)
            ->oldest()
            ->simplePaginate(12)
            ->through(fn ($order) => $this->present($order));

        return view('rider.available', compact('orders'));
    }

    public function active(Request $request)
    {
        $this->ensureReady();

        $orders = $this->activeQuery($request->user())
            ->with(self::WITH)
            ->oldest('rider_accepted_at')
            ->get()
            ->map(fn ($order) => $this->present($order));

        return view('rider.active', compact('orders'));
    }

    public function deliveries(Request $request)
    {
        $this->ensureReady();

        $orders = $this->mine($request->user())
            ->with(self::WITH)
            ->latest('rider_accepted_at')
            ->simplePaginate(12)
            ->through(fn ($order) => $this->present($order));

        return view('rider.orders', [
            'orders' => $orders,
            'page' => 'My Deliveries',
            'title' => 'My deliveries',
            'subtitle' => 'Every order assigned to you, newest first.',
            'icon' => 'package',
        ]);
    }

    public function history(Request $request)
    {
        $this->ensureReady();

        $orders = $this->mine($request->user())
            ->where('status', self::DELIVERED)
            ->with(self::WITH)
            ->latest('delivered_at')
            ->simplePaginate(12)
            ->through(fn ($order) => $this->present($order));

        return view('rider.orders', [
            'orders' => $orders,
            'page' => 'Delivery History',
            'title' => 'Delivery history',
            'subtitle' => 'Orders you have completed.',
            'icon' => 'history',
        ]);
    }

    public function accept(Request $request, int $order): RedirectResponse
    {
        $this->ensureReady();

        $rider = $request->user();

        $accepted = DB::transaction(function () use ($order, $rider) {
            $row = Order::query()
                ->whereKey($order)
                ->lockForUpdate()
                ->first();

            if (
                ! $row ||
                $this->statusOf($row) !== self::READY ||
                $row->rider_id !== null
            ) {
                return false;
            }

            $row->forceFill([
                'rider_id' => $rider->id,
                'rider_accepted_at' => now(),
            ])->save();

            return true;
        });

        return back()->with(
            $accepted ? 'success' : 'error',
            $accepted
                ? 'Order accepted. Head to the restaurant for pickup.'
                : 'Sorry, this order is no longer available.'
        );
    }

    public function pickup(Request $request, int $order): RedirectResponse
    {
        return $this->move($request, $order, 'pickup');
    }

    public function outForDelivery(Request $request, int $order): RedirectResponse
    {
        return $this->move($request, $order, 'out');
    }

    public function delivered(Request $request, int $order): RedirectResponse
    {
        return $this->move($request, $order, 'delivered');
    }

    private function move(
        Request $request,
        int $order,
        string $action
    ): RedirectResponse {
        $this->ensureReady();

        [$from, $to, $column, $message] = self::TRANSITIONS[$action];
        $rider = $request->user();

        $moved = DB::transaction(function () use (
            $order,
            $rider,
            $from,
            $to,
            $column,
            $action
        ) {
            $row = Order::query()
                ->whereKey($order)
                ->where('rider_id', $rider->id)
                ->lockForUpdate()
                ->first();

            abort_if(! $row, 404);

            if ($this->statusOf($row) !== $from) {
                return false;
            }

            $changes = ['status' => $to];

            if ($column !== null) {
                $changes[$column] = now();
            }

            $row->forceFill($changes)->save();

            if ($action === 'delivered') {
    $isCod = $row->payment_method === 'cash_on_delivery';
    $isPaidOnline = $row->payment_method === 'sslcommerz'
        && $row->payment_status === 'paid';

    if ($isCod || $isPaidOnline) {
        $commissionBase = max(
            0,
            (float) $row->subtotal - (float) $row->discount
        );

        $commissionRate = 10.00;
        $commissionAmount = round(
            $commissionBase * $commissionRate / 100,
            2
        );

        RestaurantSettlement::firstOrCreate(
            ['order_id' => $row->id],
            [
                'restaurant_id' => $row->restaurant_id,
                'order_amount' => $commissionBase,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'restaurant_amount' => round(
                    $commissionBase - $commissionAmount,
                    2
                ),
                'status' => 'pending',
            ]
        );
    }
}

            return true;
        });

        return back()->with(
            $moved ? 'success' : 'error',
            $moved
                ? $message
                : 'This order is not in the right step for that action. Please refresh.'
        );
    }

    private function ensureReady(): void
    {
        abort_unless(
            Schema::hasColumns('orders', [
                'rider_id',
                'rider_accepted_at',
                'picked_up_at',
                'delivered_at',
            ]),
            503,
            'Rider tools need a database update. Run: php artisan migrate'
        );
    }

    private function availableQuery()
    {
        return Order::query()
            ->where('status', self::READY)
            ->whereNull('rider_id');
    }

    private function mine($rider)
    {
        return Order::query()->where('rider_id', $rider->id);
    }

    private function activeQuery($rider)
    {
        return $this->mine($rider)->whereIn('status', [
            self::READY,
            self::PICKED_UP,
            self::OUT_FOR_DELIVERY,
        ]);
    }

    private function earningsToday($rider): ?float
    {
        if (! Schema::hasColumn('orders', 'delivery_fee')) {
            return null;
        }

        return (float) $this->mine($rider)
            ->where('status', self::DELIVERED)
            ->whereDate('delivered_at', today())
            ->sum('delivery_fee');
    }

    private function statusOf($order): string
    {
        return (string) (
            $order->status instanceof \BackedEnum
                ? $order->status->value
                : $order->status
        );
    }

    private function pick($model, array $keys)
    {
        if (! $model) {
            return null;
        }

        foreach ($keys as $key) {
            if (isset($model->{$key})) {
                $value = $model->{$key};

                return $value instanceof \BackedEnum
                    ? $value->value
                    : $value;
            }
        }

        return null;
    }

    private function money($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $amount = (float) $value;

        return '৳' . (
            $amount == floor($amount)
                ? number_format($amount)
                : number_format($amount, 2)
        );
    }

    private function time($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    private function maps(?string $address): ?string
    {
        return $address
            ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address)
            : null;
    }

    private function present(Order $o): array
    {
        $status = $this->statusOf($o);
        $assigned = $o->rider_id !== null;

        $address = $this->pick($o, [
            'delivery_address',
            'shipping_address',
            'address',
        ]);

        $pickup = $o->restaurant?->address;

        $step = match ($status) {
            self::DELIVERED => 3,
            self::OUT_FOR_DELIVERY => 2,
            self::PICKED_UP => 1,
            default => 0,
        };

        $action = match (true) {
            ! $assigned && $status === self::READY => [
                'Accept order',
                route('rider.orders.accept', $o->getKey()),
                'check',
            ],
            $assigned && $status === self::READY => [
                'Confirm pickup',
                route('rider.orders.pickup', $o->getKey()),
                'package-check',
            ],
            $assigned && $status === self::PICKED_UP => [
                'Out for delivery',
                route('rider.orders.out', $o->getKey()),
                'truck',
            ],
            $assigned && $status === self::OUT_FOR_DELIVERY => [
                'Mark as delivered',
                route('rider.orders.delivered', $o->getKey()),
                'circle-check',
            ],
            default => null,
        };

        return [
            'id' => $o->getKey(),
            'number' => $this->pick($o, [
                'order_number',
                'number',
                'code',
            ]) ?? '#' . $o->getKey(),
            'status' => $status,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'cash_collected_url' => route(
                'rider.orders.cash-collected',
                $o->getKey()
            ),
            'step' => $step,
            'action' => $action,
            'restaurant' => $o->restaurant?->name,
            'pickup_address' => $pickup,
            'pickup_map' => $this->maps($pickup),
            'customer' => $o->customer?->name
                ?? $this->pick($o, ['customer_name']),
            'phone' => $this->pick($o, [
                'customer_phone',
                'phone',
            ]) ?? $this->pick($o->customer, [
                'phone',
                'phone_number',
                'mobile',
            ]),
            'address' => $address,
            'address_map' => $this->maps($address),
            'total' => $this->money($this->pick($o, [
                'total',
                'total_amount',
                'total_price',
                'grand_total',
            ])),
            'fee' => $this->money($this->pick($o, [
                'delivery_fee',
                'delivery_charge',
                'shipping_fee',
            ])),
            'items' => $o->relationLoaded('items')
                ? $o->items->sum('quantity')
                : null,
            'posted' => $o->created_at,
            'accepted_at' => $this->time($o->rider_accepted_at),
            'delivered_at' => $this->time($o->delivered_at),
        ];
    }
    public function updateLocation(Request $request, int $order)
{
    $validated = $request->validate([
        'latitude'  => ['required', 'numeric', 'between:-90,90'],
        'longitude' => ['required', 'numeric', 'between:-180,180'],
        'heading'   => ['nullable', 'numeric', 'between:0,360'],
        'speed'     => ['nullable', 'numeric', 'min:0'],
    ]);

    $delivery = Order::query()
        ->whereKey($order)
        ->where('rider_id', $request->user()->id)
        ->whereIn('status', [
            self::PICKED_UP,
            self::OUT_FOR_DELIVERY,
        ])
        ->first();

    if (! $delivery) {
        return response()->json([
            'message' => 'Active assigned delivery not found.',
        ], 403);
    }

    $location = RiderLocation::updateOrCreate(
    [
        'order_id' => $delivery->id,
        'rider_id' => $request->user()->id,
    ],
    [
        'latitude' => $validated['latitude'],
        'longitude' => $validated['longitude'],
        'heading' => $validated['heading'] ?? null,
        'speed' => $validated['speed'] ?? null,
        'recorded_at' => now(),
    ]
);

    return response()->json([
        'message' => 'Location updated successfully.',
        'recorded_at' => $location->recorded_at,
    ]);
}

public function cashCollected(
    Request $request,
    int $order
): RedirectResponse {
    $this->ensureReady();

    $updated = DB::transaction(function () use ($request, $order) {
        $row = Order::query()
            ->whereKey($order)
            ->where('rider_id', $request->user()->id)
            ->lockForUpdate()
            ->first();

        abort_if(! $row, 404);

        if ($this->statusOf($row) !== self::DELIVERED) {
            return 'not_delivered';
        }

        if ($row->payment_method !== 'cash_on_delivery') {
            return 'not_cod';
        }

        if ($row->payment_status === 'paid') {
            return 'already_paid';
        }

        if ($row->payment_status !== 'pending') {
            return 'invalid_payment_status';
        }

        $row->forceFill([
            'payment_status' => 'paid',
        ])->save();

        return 'paid';
    });

    return back()->with(
        in_array($updated, ['paid', 'already_paid'], true)
            ? 'success'
            : 'error',
        match ($updated) {
            'paid' => 'Cash collection confirmed. Payment marked as paid.',
            'already_paid' => 'This order is already marked as paid.',
            'not_delivered' => 'Only delivered orders can be marked as paid.',
            'not_cod' => 'Cash collection is only available for COD orders.',
            default => 'Payment status is invalid. Please check the order.',
        }
    );
}
}
