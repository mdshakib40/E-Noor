<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantSettlement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Dashboard chart range: 7, 30 or 90 days
        $rangeDays = in_array(
            (int) request('range', 7),
            [7, 30, 90],
            true
        ) ? (int) request('range', 7) : 7;

        // Users
        $users = User::latest()->get();

        $customersCount = User::where(
            'role',
            UserRole::CUSTOMER->value
        )->count();

        $riders = User::where(
            'role',
            UserRole::RIDER->value
        )->latest()->get();

        // Main statistics
        $ordersCount = Order::count();

        $restaurantsCount = Restaurant::count();

        // Exclude cancelled and rejected orders from platform revenue
        $platformRevenue = (float) Order::whereNotIn(
            'status',
            ['cancelled', 'rejected']
        )->sum('total');

        // Online payments
        $paidOnlineOrders = Order::where(
            'payment_status',
            'paid'
        )
            ->where('payment_method', 'sslcommerz')
            ->count();

        // Restaurant commission and settlements
        $totalRestaurantCommission = (float)
            RestaurantSettlement::sum('commission_amount');

        $totalRestaurantPayable = (float)
            RestaurantSettlement::sum('restaurant_amount');

        $pendingSettlementAmount = (float)
            RestaurantSettlement::where('status', 'pending')
                ->sum('restaurant_amount');

        $paidSettlementAmount = (float)
            RestaurantSettlement::where('status', 'paid')
                ->sum('restaurant_amount');

        $recentSettlements = RestaurantSettlement::with([
            'restaurant',
            'order',
        ])
            ->latest()
            ->take(10)
            ->get();

        // Latest orders
        $recentOrders = Order::with([
            'restaurant',
            'customer',
        ])
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($order) {
                $status = strtolower(
                    (string) ($order->status ?? 'pending')
                );

                $statusLabels = [
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'preparing' => 'Preparing',
                    'ready' => 'Ready',
                    'out_for_delivery' => 'On the way',
                    'delivered' => 'Delivered',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'rejected' => 'Rejected',
                ];

                // Keep these class names compatible with the
                // existing dashboard badge styles.
                $statusClasses = [
                    'pending' => 'gold',
                    'confirmed' => 'blue',
                    'preparing' => 'blue',
                    'ready' => 'blue',
                    'out_for_delivery' => 'blue',
                    'delivered' => 'green',
                    'completed' => 'green',
                    'cancelled' => 'grey',
                    'rejected' => 'grey',
                ];

                $order->display_id = $order->order_number
                    ?: $order->id;

                $order->customer_name = $order->customer_name
                    ?: optional($order->customer)->name
                    ?: 'Guest';

                $order->restaurant_name = optional(
                    $order->restaurant
                )->name ?? 'Unknown restaurant';

                $order->status_label = $statusLabels[$status]
                    ?? ucfirst(str_replace('_', ' ', $status));

                $order->status_class = $statusClasses[$status]
                    ?? 'gold';

                $order->amount = (float) $order->total;

                return $order;
            });

        // Revenue and order counts for the selected date range
        $dailyStats = collect();

        for ($i = $rangeDays - 1; $i >= 0; $i--) {
            $date = now()->startOfDay()->subDays($i);

            $dayOrders = Order::query()->whereDate(
                'created_at',
                $date->toDateString()
            );

            $dailyStats->push((object) [
                'label' => $rangeDays === 7
                    ? $date->format('D')
                    : $date->format('d M'),

                'revenue' => (float) (clone $dayOrders)
                    ->whereNotIn('status', ['cancelled', 'rejected'])
                    ->sum('total'),

                'orders' => (clone $dayOrders)->count(),
            ]);
        }

        // Order status distribution
        $statusRows = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $statusColors = [
            'pending' => '#d7a858',
            'confirmed' => '#b8d985',
            'preparing' => '#b8d985',
            'ready' => '#b8d985',
            'out_for_delivery' => '#b8d985',
            'delivered' => '#789c57',
            'completed' => '#789c57',
            'cancelled' => '#625e55',
            'rejected' => '#625e55',
        ];

        $orderStatusStats = $statusRows->map(
            function ($row) use ($ordersCount, $statusColors) {
                $status = strtolower(
                    (string) ($row->status ?? 'pending')
                );

                return (object) [
                    'label' => ucfirst(
                        str_replace('_', ' ', $status)
                    ),

                    'total' => (int) $row->total,

                    'percent' => $ordersCount > 0
                        ? (int) round(
                            ($row->total / $ordersCount) * 100
                        )
                        : 0,

                    'class' => 'status-' . $status,

                    'color' => $statusColors[$status]
                        ?? '#625e55',
                ];
            }
        );

        // Donut chart
        $segments = [];
        $position = 0;

        foreach ($orderStatusStats as $stat) {
            if ($stat->percent <= 0) {
                continue;
            }

            $nextPosition = $position + $stat->percent;

            $segments[] = "{$stat->color} {$position}% {$nextPosition}%";

            $position = $nextPosition;
        }

        $orderMixStyle = $segments
            ? 'conic-gradient(' . implode(', ', $segments) . ')'
            : 'conic-gradient(#3b3932 0% 100%)';

        // Top restaurants by order value this week
        $weekStart = now()->startOfWeek();

        $topRestaurants = Restaurant::query()
            ->leftJoin('orders', function ($join) use ($weekStart) {
                $join->on(
                    'restaurants.id',
                    '=',
                    'orders.restaurant_id'
                )->where(
                    'orders.created_at',
                    '>=',
                    $weekStart
                );
            })
            ->select(
                'restaurants.id',
                'restaurants.name',
                DB::raw(
                    'COALESCE(SUM(orders.total), 0) as revenue'
                )
            )
            ->groupBy('restaurants.id', 'restaurants.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $maxRestaurantRevenue = max(
            1,
            (float) $topRestaurants->max('revenue')
        );

        $topRestaurants->transform(
            function ($restaurant) use ($maxRestaurantRevenue) {
                $restaurant->bar_percent = min(
                    100,
                    round(
                        (
                            (float) $restaurant->revenue
                            / $maxRestaurantRevenue
                        ) * 100
                    )
                );

                return $restaurant;
            }
        );

        // Rider active orders, if the column exists
        $hasRiderColumn = Schema::hasColumn('orders', 'rider_id');

        foreach ($riders as $rider) {
            $rider->active_order_count = $hasRiderColumn
                ? Order::where('rider_id', $rider->id)
                    ->whereIn('status', [
                        'confirmed',
                        'preparing',
                        'ready',
                        'out_for_delivery',
                    ])
                    ->count()
                : 0;
        }

        return view('admin.dashboard', compact(
            'users',
            'customersCount',
            'riders',
            'ordersCount',
            'platformRevenue',
            'restaurantsCount',
            'recentOrders',
            'dailyStats',
            'orderStatusStats',
            'orderMixStyle',
            'topRestaurants',
            'rangeDays',
            'totalRestaurantCommission',
            'totalRestaurantPayable',
            'pendingSettlementAmount',
            'paidSettlementAmount',
            'paidOnlineOrders',
            'recentSettlements'
        ));
    }
}
