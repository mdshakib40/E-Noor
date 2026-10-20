<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $column
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
}
