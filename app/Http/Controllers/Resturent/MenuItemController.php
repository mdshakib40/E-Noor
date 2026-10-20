<?php

namespace App\Http\Controllers\Resturent;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    
public function show(MenuItem $menuItem)
{
    // Only show active food items from active restaurants.
    abort_unless(
        $menuItem->status === 'active'
        && $menuItem->is_available
        && $menuItem->restaurant
        && $menuItem->restaurant->status === 'active',
        404
    );

    $menuItem->load('restaurant');

    // Related food from different restaurants too.
    $relatedItems = MenuItem::with('restaurant')
        ->where('id', '!=', $menuItem->id)
        ->where('status', 'active')
        ->where('is_available', true)
        ->whereHas('restaurant', function ($query) {
            $query->where('status', 'active');
        })
        ->inRandomOrder()
        ->take(8)
        ->get();

    return view('resturent.food.show', compact(
        'menuItem',
        'relatedItems'
    ));
}

    public function index()
    {
        $restaurant = $this->ownerRestaurant();

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
        $restaurant = $this->ownerRestaurant();

        return view('resturent.menu.create', compact('restaurant'));
    }

    /**
     * Works for both the fetch() request from the Add Food Item page (JSON)
     * and a normal form POST (redirect).
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $restaurant = $this->ownerRestaurant();

        // On failure Laravel returns 422 JSON for fetch(), or redirects back with errors.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999.99'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('menu-items', 'public');
        }

        try {
            $attempts = 5;

            for ($attempt = 1; $attempt <= $attempts; $attempt++) {
                try {
                    // restaurant_id is set by the relationship, never taken from the request.
                    $restaurant->menuItems()->create([
                        'name' => $validated['name'],
                        'slug' => $this->uniqueSlug($restaurant, $validated['name']),
                        'description' => $validated['description'] ?? null,
                        'price' => $validated['price'],
                        'image' => $imagePath,
                        'status' => $validated['status'],
                        'is_available' => $request->boolean('is_available'),
                    ]);

                    break;
                } catch (UniqueConstraintViolationException $e) {
                    // Two requests raced for the same slug: try again with a fresh one.
                    if ($attempt === $attempts) {
                        throw $e;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Don't leave an orphaned upload behind if saving failed.
            $this->deleteImage($imagePath);

            throw $e;
        }

        $message = 'Food item added successfully.';

        if ($request->expectsJson()) {
            // The menu list page reads this flash message after the JS redirect.
            $request->session()->flash('success', $message);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('resturent.menu.index'),
            ], 201);
        }

        return redirect()
            ->route('resturent.menu.index')
            ->with('success', $message);
    }

    public function edit(MenuItem $menuItem)
    {
        $restaurant = $this->ownerRestaurant();
        $this->authorizeItem($menuItem, $restaurant);

        return view('resturent.menu.edit', compact(
            'restaurant',
            'menuItem'
        ));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $restaurant = $this->ownerRestaurant();
        $this->authorizeItem($menuItem, $restaurant);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999.99'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        if ($menuItem->name !== $validated['name']) {
            $menuItem->slug = $this->uniqueSlug($restaurant, $validated['name'], $menuItem->id);
        }

        $oldImage = $menuItem->image;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')
                ->store('menu-items', 'public');

            $menuItem->image = $newImage;
        }

        $menuItem->name = $validated['name'];
        $menuItem->description = $validated['description'] ?? null;
        $menuItem->price = $validated['price'];
        $menuItem->status = $validated['status'];
        $menuItem->is_available = $request->boolean('is_available');

        try {
            $menuItem->save();
        } catch (\Throwable $e) {
            $this->deleteImage($newImage);

            throw $e;
        }

        // Remove the replaced image only after the new one is safely saved.
        if ($newImage && $oldImage) {
            $this->deleteImage($oldImage);
        }

        return redirect()
            ->route('resturent.menu.index')
            ->with('success', 'Food item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $restaurant = $this->ownerRestaurant();
        $this->authorizeItem($menuItem, $restaurant);

        $image = $menuItem->image;

        $menuItem->delete();

        $this->deleteImage($image);

        return redirect()
            ->route('resturent.menu.index')
            ->with('success', 'Food item deleted successfully.');
    }

    /**
     * The authenticated owner's own restaurant (404 if they have none).
     */
    private function ownerRestaurant(): Restaurant
    {
        $restaurant = Auth::user()->restaurants()->first();

        if (! $restaurant) {
            abort(404, 'Restaurant not found.');
        }

        return $restaurant;
    }

    /**
     * Make sure the menu item belongs to the owner's restaurant.
     */
    private function authorizeItem(MenuItem $menuItem, Restaurant $restaurant): void
    {
        if ((int) $menuItem->restaurant_id !== (int) $restaurant->id) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Slug that is unique inside this restaurant (food-name, food-name-1, food-name-2...).
     */
    private function uniqueSlug(Restaurant $restaurant, string $name, ?int $ignoreId = null): string
    {
        $originalSlug = Str::slug($name) ?: 'food-item';
        $slug = $originalSlug;
        $counter = 1;

        while (
            $restaurant->menuItems()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}