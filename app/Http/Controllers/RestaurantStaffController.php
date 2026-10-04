<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class RestaurantStaffController extends Controller
{
    /**
     * Show restaurant staff.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== UserRole::RESTAURANT_OWNER) {
            abort(403, 'Only restaurant owners can manage staff.');
        }

        $restaurant = $user->restaurants()->first();

            if (! $restaurant) {
                return redirect()
                    ->route('resturent.dashboard')
                    ->with('error', 'Please create your restaurant first.');
            }

            $staff = $restaurant->staff()
                ->where('role', UserRole::RESTAURANT_STAFF)
                ->latest()
                ->get();

            return view('resturent.staff.index', compact(
                'restaurant',
                'staff'
            ));
    }

    /**
 * Show the add staff form.
 */
public function create()
{
    $user = Auth::user();

    if ($user->role !== UserRole::RESTAURANT_OWNER) {
        abort(403, 'Only restaurant owners can add staff.');
    }

    $restaurant = $user->restaurants()->first();

    if (! $restaurant) {
        return redirect()
            ->route('resturent.dashboard')
            ->with('error', 'Please create your restaurant first.');
    }

    return view('resturent.staff.create', compact('restaurant'));
}

public function store(Request $request)
{
    $user = Auth::user();

    if ($user->role !== UserRole::RESTAURANT_OWNER) {
        abort(403, 'Only restaurant owners can add staff.');
    }

    $restaurant = $user->restaurants()->first();

    if (! $restaurant) {
        return redirect()
            ->route('resturent.dashboard')
            ->with('error', 'Please create your restaurant first.');
    }

    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
        ],

        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],
    ]);

    $staff = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => $validated['password'],
        'role' => UserRole::RESTAURANT_STAFF,
    ]);

    $staff->restaurant_id = $restaurant->id;
    $staff->save();

    return redirect()
        ->route('resturent.staff.index')
        ->with('success', 'Staff member added successfully.');
}
}