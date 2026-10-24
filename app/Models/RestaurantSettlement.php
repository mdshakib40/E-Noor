<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantSettlement extends Model
{
    protected $fillable = [
        'restaurant_id',
        'order_id',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'restaurant_amount',
        'status',
        'settled_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_amount' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'restaurant_amount' => 'decimal:2',
            'settled_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}