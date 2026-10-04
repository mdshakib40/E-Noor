<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantStaffController;
use App\Http\Controllers\Resturent\OrderController;
use App\Enums\UserRole;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/show', function () {
    return view('resturent.show');
});

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
        return view('customer.dashboard');
    })->name('customer.dashboard');

});


/*
|--------------------------------------------------------------------------
| Super Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super_admin'])->group(function () {

    Route::get('/admin/dashboard', function () {

        $users = \App\Models\User::latest()->get();

        return view('admin.dashboard', compact('users'));

    })->name('admin.dashboard');


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

Route::middleware(['auth', 'role:rider'])->group(function () {

    Route::get('/rider/dashboard', function () {
        return view('rider.dashboard');
    })->name('rider.dashboard');

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

    Route::get('/resturent/dashboard', function () {
        return view('resturent.dashboard');
    })->name('resturent.dashboard');

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

