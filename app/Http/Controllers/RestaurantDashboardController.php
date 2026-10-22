<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Find the restaurant belonging to the logged-in user.
        if ($user->role === UserRole::RESTAURANT_OWNER) {
            $restaurant = $user->restaurants()->first();
        } else {
            $restaurant = Restaurant::where(
                'id',
                $user->restaurant_id
            )->first();
        }

        if (! $restaurant) {
            if ($user->role === UserRole::RESTAURANT_OWNER) {
                return redirect()
                    ->route('resturent.restaurant.create')
                    ->with('error', 'Please create your restaurant first.');
            }

            abort(403, 'No restaurant is assigned to this account.');
        }

        // Scope every order query to this restaurant.
        $orders = Order::where('restaurant_id', $restaurant->id);

        $totalOrders = (clone $orders)->count();

        $pendingOrders = (clone $orders)
            ->where('status', 'pending')
            ->count();

        $completedOrders = (clone $orders)
            ->where('status', 'completed')
            ->count();

        // Revenue from completed orders.
        $totalRevenue = (clone $orders)
            ->where('status', 'completed')
            ->sum('total');

        $menuItemsCount = $restaurant->menuItems()->count();

        $recentOrders = (clone $orders)
            ->with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('resturent.dashboard', compact(
            'restaurant',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'menuItemsCount',
            'recentOrders'
        ));
    }
}
