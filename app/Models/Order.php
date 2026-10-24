<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'order_number',
        'subtotal',
        'delivery_fee',
        'discount',
        'total',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'status',
        'payment_status',
        'payment_method',
        'notes',
        'delivery_zone',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(RestaurantSettlement::class);
    }
}
