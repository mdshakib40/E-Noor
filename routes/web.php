<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantStaffController;
use App\Http\Controllers\Resturent\OrderController;
use App\Http\Controllers\Resturent\MenuItemController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartPageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Rider\RiderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Customer\OrderTrackingController;
use App\Http\Controllers\RestaurantDashboardController;
use App\Enums\UserRole;
use App\Models\Restaurant;
use App\Models\Order;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [RestaurantController::class, 'index'])
    ->name('home');

Route::get('/restaurants/{restaurant}', [
    RestaurantController::class,
    'show'
])->name('restaurants.show');

Route::get('/foods/{food}', [
    FoodController::class,
    'show'
])->whereNumber('food')->name('foods.show');

Route::get('/app', function () {
    return view('layouts.app');
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::get('/login', function () {
    return redirect('/')
        ->with('open_auth_modal', 'login');
})->name('login');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('password.forgot');

Route::post('/verify-password-code', [AuthController::class, 'verifyPasswordCode'])
    ->name('password.verify.code');

Route::post('/update-password', [AuthController::class, 'updatePassword'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| Common Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $user = Auth::user();

    return match ($user->role) {

        UserRole::SUPER_ADMIN =>
            redirect()->route('admin.dashboard'),

        UserRole::CUSTOMER =>
            redirect()->route('customer.dashboard'),

        UserRole::RIDER =>
            redirect()->route('rider.dashboard'),

        UserRole::RESTAURANT_OWNER =>
            redirect()->route('resturent.dashboard'),

        UserRole::RESTAURANT_STAFF =>
            redirect()->route('resturent.staff.dashboard'),

        default =>
            abort(403, 'Unauthorized role.'),
    };

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| Customer Dashboard
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', function () {
        $user = Auth::user();

        $orders = Order::with('restaurant')
            ->where('customer_id', $user->id)
            ->latest()
            ->get();

        $activeOrders = $orders->whereNotIn('status', [
            'delivered',
            'completed',
            'cancelled',
            'rejected',
        ]);

        $activeOrder = $activeOrders->first();

        $totalOrders = $orders->count();

        $completedOrders = $orders->whereIn('status', [
            'delivered',
            'completed',
        ])->count();

        $totalSpent = $orders
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total');

        $recentOrders = $orders->take(5);

        return view('customer.dashboard', compact(
            'user',
            'orders',
            'activeOrders',
            'activeOrder',
            'totalOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
    })->name('customer.dashboard');

    
Route::get('/customer/orders/{order}', function (Order $order) {
    abort_unless(
        $order->customer_id === Auth::id(),
        403
    );

    $order->load(['restaurant', 'items']);

    return view('customer.orders.show', compact('order'));
})->name('customer.orders.show');
Route::get(
    '/customer/orders/{order}/tracking',
    [OrderTrackingController::class, 'show']
)->whereNumber('order')->name('customer.orders.tracking');


});




/*
|--------------------------------------------------------------------------
| Super Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin'])->group(function () {

    Route::get('/admin/dashboard', [
        AdminDashboardController::class,
        'index'
    ])->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | User Role Update
    |--------------------------------------------------------------------------
    */

    Route::patch('/admin/users/{user}/role', [
        AdminUserController::class,
        'updateRole'
    ])->name('admin.users.role.update');

});


/*
|--------------------------------------------------------------------------
| Rider Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:rider'])->prefix('rider')->name('rider.')->group(function () {
    Route::get('/dashboard', [RiderController::class, 'index'])->name('dashboard');
    Route::get('/orders/available', [RiderController::class, 'available'])->name('available');
    Route::get('/deliveries', [RiderController::class, 'deliveries'])->name('deliveries');
    Route::get('/active', [RiderController::class, 'active'])->name('active');
    Route::get('/history', [RiderController::class, 'history'])->name('history');

    Route::post('/orders/{order}/accept', [RiderController::class, 'accept'])->whereNumber('order')->name('orders.accept');
    Route::post('/orders/{order}/pickup', [RiderController::class, 'pickup'])->whereNumber('order')->name('orders.pickup');
    Route::post('/orders/{order}/out-for-delivery', [RiderController::class, 'outForDelivery'])->whereNumber('order')->name('orders.out');
    Route::post('/orders/{order}/delivered', [RiderController::class, 'delivered'])->whereNumber('order')->name('orders.delivered');
    Route::post(
    '/orders/{order}/location',
    [RiderController::class, 'updateLocation']
    )->name('orders.location');

});


/*
|--------------------------------------------------------------------------
| Restaurant Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:restaurant_owner,restaurant_staff'
])->group(function () {

    Route::get('/resturent/dashboard', [
    RestaurantDashboardController::class,
    'index'
])->name('resturent.dashboard');

    Route::get('/resturent/menu', [
    \App\Http\Controllers\Resturent\MenuItemController::class,
    'index'
])->name('resturent.menu.index');

Route::get('/resturent/menu/create', [
    \App\Http\Controllers\Resturent\MenuItemController::class,
    'create'
])->name('resturent.menu.create');

Route::post('/resturent/menu', [
    \App\Http\Controllers\Resturent\MenuItemController::class,
    'store'
])->name('resturent.menu.store');

Route::get('/resturent/menu/{menuItem}/edit', [
    \App\Http\Controllers\Resturent\MenuItemController::class,
    'edit'
])->name('resturent.menu.edit');

Route::put('/resturent/menu/{menuItem}', [
    \App\Http\Controllers\Resturent\MenuItemController::class,
    'update'
])->name('resturent.menu.update');

Route::delete('/resturent/menu/{menuItem}', [
    \App\Http\Controllers\Resturent\MenuItemController::class,
    'destroy'
])->name('resturent.menu.destroy');

});


/*
|--------------------------------------------------------------------------
| Restaurant Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:restaurant_owner'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Restaurant Management
    |--------------------------------------------------------------------------
    */

    Route::get('/resturent/restaurant/create', [
        RestaurantController::class,
        'create'
    ])->name('resturent.restaurant.create');

    Route::post('/resturent/restaurant', [
        RestaurantController::class,
        'store'
    ])->name('resturent.restaurant.store');

    Route::get('/resturent/restaurant/edit', [
        RestaurantController::class,
        'edit'
    ])->name('resturent.restaurant.edit');

    Route::put('/resturent/restaurant', [
        RestaurantController::class,
        'update'
    ])->name('resturent.restaurant.update');


    /*
    |--------------------------------------------------------------------------
    | Restaurant Staff Management
    |--------------------------------------------------------------------------
    */

    Route::get('/resturent/staff', [
        RestaurantStaffController::class,
        'index'
    ])->name('resturent.staff.index');

    Route::get('/resturent/staff/create', [
        RestaurantStaffController::class,
        'create'
    ])->name('resturent.staff.create');

    Route::post('/resturent/staff', [
        RestaurantStaffController::class,
        'store'
    ])->name('resturent.staff.store');


    /*
    |--------------------------------------------------------------------------
    | Restaurant Orders
    |--------------------------------------------------------------------------
    */

    Route::get('/resturent/orders', [
        OrderController::class,
        'index'
    ])->name('resturent.orders.index');

    Route::get('/resturent/orders/{order}', [
        OrderController::class,
        'show'
    ])->name('resturent.orders.show');

    Route::patch(
    '/resturent/orders/{order}/status',
    [\App\Http\Controllers\Resturent\OrderController::class, 'updateStatus']
)->name('resturent.orders.updateStatus');

});


/*
|--------------------------------------------------------------------------
| Restaurant Staff Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:restaurant_staff'
])->group(function () {

    Route::get('/resturent/staff/dashboard', function () {
        return view('resturent.staff.dashboard');
    })->name('resturent.staff.dashboard');

});

Route::middleware('auth')->post('/cart/add', [CartController::class, 'store'])
    ->name('cart.add');

    Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartPageController::class, 'index'])->name('index');
    Route::patch('/{cartItem}', [CartPageController::class, 'update'])->name('update');
    Route::delete('/{cartItem}', [CartPageController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');
});
