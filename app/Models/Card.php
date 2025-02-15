<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    protected $fillable = [
        'set_id',
        'card_id',
        'name',
        'full_name',
        'type',
        'color',
        'rarity',
        'cost',
        'strength',
        'willpower',
        'lore',
        'inkwell',
        'abilities',
        'flavor_text',
        'full_text',
        'story',
        'image_url',
        'thumbnail_url',
        'subtypes',
        'artists',
        'version',
    ];

    protected $casts = [
        'inkwell' => 'boolean',
        'abilities' => 'array',
        'subtypes' => 'array',
        'artists' => 'array',
    ];

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    public function userCards(): HasMany
    {
        return $this->hasMany(UserCard::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
