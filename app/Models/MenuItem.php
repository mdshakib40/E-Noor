<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'status',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where('is_available', true)
            ->whereHas('restaurant', function (Builder $restaurantQuery) {
                $restaurantQuery->where('status', 'active');
            });
    }
}
