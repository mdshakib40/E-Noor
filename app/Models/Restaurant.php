<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\UserRole;
use App\Models\MenuItem;

class Restaurant extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'phone',
        'address',
        'image',
        'status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(User::class, 'restaurant_id')
            ->where('role', UserRole::RESTAURANT_STAFF);
    }

    public function menuItems(): HasMany
        {
            return $this->hasMany(MenuItem::class);
        }
}