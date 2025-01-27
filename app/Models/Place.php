<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_id',
        'name',
        'description',
        'category_id',
        'longitude',
        'latitude'
    ];

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TravelCategory::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
