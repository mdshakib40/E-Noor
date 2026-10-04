<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    /**
     * Show restaurant creation form.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== UserRole::RESTAURANT_OWNER) {
            abort(403, 'Only restaurant owners can create a restaurant.');
        }

        return view('resturent.restaurant.create');
    }

    /**
     * Store a new restaurant.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== UserRole::RESTAURANT_OWNER) {
            abort(403, 'Only restaurant owners can create a restaurant.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('restaurants', 'public');
        }

        Restaurant::create([
            'owner_id' => $user->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'image' => $imagePath,
            'status' => 'active',
        ]);

        return redirect()
            ->route('resturent.dashboard')
            ->with('success', 'Your restaurant has been created successfully.');
    }

    /**
     * Show restaurant edit form.
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== UserRole::RESTAURANT_OWNER) {
            abort(403, 'Only restaurant owners can edit a restaurant.');
        }

        $restaurant = $user->restaurants()->first();

        if (! $restaurant) {
            return redirect()
                ->route('resturent.dashboard')
                ->with('error', 'Please create your restaurant first.');
        }

        return view('resturent.restaurant.edit', compact('restaurant'));
    }

    /**
     * Update restaurant details.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== UserRole::RESTAURANT_OWNER) {
            abort(403, 'Only restaurant owners can update a restaurant.');
        }

        $restaurant = $user->restaurants()->first();

        if (! $restaurant) {
            return redirect()
                ->route('resturent.dashboard')
                ->with('error', 'Restaurant not found.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $restaurant->name = $validated['name'];
        $restaurant->description = $validated['description'] ?? null;
        $restaurant->phone = $validated['phone'] ?? null;
        $restaurant->address = $validated['address'] ?? null;

        if ($request->hasFile('image')) {
            $restaurant->image = $request
                ->file('image')
                ->store('restaurants', 'public');
        }

        $restaurant->save();

        return redirect()
            ->route('resturent.restaurant.edit')
            ->with('success', 'Restaurant details updated successfully.');
    }
}