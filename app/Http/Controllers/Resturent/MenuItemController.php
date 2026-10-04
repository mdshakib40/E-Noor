<?php

namespace App\Http\Controllers\Resturent;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        $menuItems = $restaurant->menuItems()
            ->latest()
            ->get();

        return view('resturent.menu.index', compact(
            'restaurant',
            'menuItems'
        ));
    }

    public function create()
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        return view('resturent.menu.create', compact('restaurant'));
    }

    public function store(Request $request)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;
        $counter = 1;

        while (
            $restaurant->menuItems()
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('menu-items', 'public');
        }

        $restaurant->menuItems()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()
            ->route('resturent.menu.index')
            ->with('success', 'Food item added successfully.');
    }

    public function edit(MenuItem $menuItem)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('resturent.menu.edit', compact(
            'restaurant',
            'menuItem'
        ));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        if ($menuItem->name !== $validated['name']) {

            $slug = Str::slug($validated['name']);

            $originalSlug = $slug;
            $counter = 1;

            while (
                $restaurant->menuItems()
                    ->where('slug', $slug)
                    ->where('id', '!=', $menuItem->id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $menuItem->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $menuItem->image = $request->file('image')
                ->store('menu-items', 'public');
        }

        $menuItem->name = $validated['name'];
        $menuItem->description = $validated['description'] ?? null;
        $menuItem->price = $validated['price'];
        $menuItem->status = $validated['status'];
        $menuItem->is_available = $request->boolean('is_available');

        $menuItem->save();

        return redirect()
            ->route('resturent.menu.index')
            ->with('success', 'Food item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        if ($menuItem->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }

        $menuItem->delete();

        return redirect()
            ->route('resturent.menu.index')
            ->with('success', 'Food item deleted successfully.');
    }
}