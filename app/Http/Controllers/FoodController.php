<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Contracts\View\View;

class FoodController extends Controller
{
    /**
     * Public food details page.
     * Only active + available items from active restaurants are visible (404 otherwise).
     */
    public function show(int $food): View
    {
        $menuItem = MenuItem::visible()
            ->with('restaurant')
            ->findOrFail($food);

        // A pool of other visible items, then prefer one dish per restaurant.
        $pool = MenuItem::visible()
            ->with('restaurant')
            ->whereKeyNot($menuItem->getKey())
            ->inRandomOrder()
            ->limit(60)
            ->get();

        $distinct = $pool->unique('restaurant_id');

        // Different restaurants first; top up with remaining dishes if there are fewer than 8.
        $relatedItems = $distinct
            ->concat($pool->diff($distinct))
            ->take(8)
            ->values();

        return view('resturent.food.show', compact('menuItem', 'relatedItems'));
    }
}