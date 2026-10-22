<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $users = User::latest()->get();

        $customersCount = User::where(
            'role',
            UserRole::CUSTOMER->value
        )->count();

        $riders = User::where(
            'role',
            UserRole::RIDER->value
        )->latest()->get();

        // Order statistics
        $ordersCount = Order::count();

        $platformRevenue = (float) Order::sum('total');

        $restaurantsCount = Restaurant::count();

        // Recent orders
        $recentOrders = Order::with('restaurant')
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

                $statusClasses = [
                    'pending' => 'pending',
                    'confirmed' => 'preparing',
                    'preparing' => 'preparing',
                    'ready' => 'preparing',
                    'out_for_delivery' => 'preparing',
                    'delivered' => 'delivered',
                    'completed' => 'delivered',
                    'cancelled' => 'cancelled',
                    'rejected' => 'cancelled',
                ];

                $order->display_id = $order->order_number
                    ?: $order->id;

                $order->customer_name = $order->customer_name
                    ?: optional($order->customer)->name;

                $order->restaurant_name = optional(
                    $order->restaurant
                )->name;

                $order->status_label = $statusLabels[$status]
                    ?? ucfirst(str_replace('_', ' ', $status));

                $order->status_class = $statusClasses[$status]
                    ?? 'pending';

                $order->amount = (float) $order->total;

                return $order;
            });

        // Revenue and order counts for the last 7 days
        $dailyStats = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->startOfDay()->subDays($i);

            $dayOrders = Order::whereDate(
                'created_at',
                $date->toDateString()
            );

            $dailyStats->push((object) [
                'label' => $date->format('D'),
                'revenue' => (float) (clone $dayOrders)->sum('total'),
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

        $orderStatusStats = $statusRows->map(function ($row) use (
            $ordersCount,
            $statusColors
        ) {
            $status = strtolower((string) ($row->status ?? 'pending'));

            return (object) [
                'label' => ucfirst(str_replace('_', ' ', $status)),
                'total' => (int) $row->total,
                'percent' => $ordersCount > 0
                    ? (int) round(($row->total / $ordersCount) * 100)
                    : 0,
                'class' => 'status-' . $status,
                'color' => $statusColors[$status] ?? '#625e55',
            ];
        });

        // Build the donut chart from real order status counts
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
                $join->on('restaurants.id', '=', 'orders.restaurant_id')
                    ->where('orders.created_at', '>=', $weekStart);
            })
            ->select(
                'restaurants.id',
                'restaurants.name',
                DB::raw('COALESCE(SUM(orders.total), 0) as revenue')
            )
            ->groupBy('restaurants.id', 'restaurants.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $maxRestaurantRevenue = max(
            1,
            (float) $topRestaurants->max('revenue')
        );

        $topRestaurants->transform(function ($restaurant) use (
            $maxRestaurantRevenue
        ) {
            $restaurant->bar_percent = min(
                100,
                round(
                    ((float) $restaurant->revenue / $maxRestaurantRevenue) * 100
                )
            );

            return $restaurant;
        });

        // Count active orders per rider only if orders.rider_id exists.
        // The Order model supplied so far does not define this relationship.
        $hasRiderColumn = Schema::hasColumn('orders', 'rider_id');
        $rangeDays = in_array((int) request('range', 7), [7, 30, 90])
        ? (int) request('range', 7)
        : 7;

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
            'rangeDays'
        ));
    }
}
