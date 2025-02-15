<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'release_date',
        'prerelease_date',
        'has_all_cards',
    ];

    protected $casts = [
        'release_date' => 'date',
        'prerelease_date' => 'date',
        'has_all_cards' => 'boolean',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
