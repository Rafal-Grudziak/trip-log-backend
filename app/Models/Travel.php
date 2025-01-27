<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';


    protected $fillable = [
        'name',
        'description',
        'from',
        'to',
        'longitude',
        'latitude',
        'favourite',
        'user_id',
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
        'longitude' => 'double',
        'latitude' => 'double',
        'favourite' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function scopePlanned(Builder $query): Builder
    {
        return $query->where('to', '>', Carbon::now());
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('to', '<', Carbon::now());
    }

    public function scopeFavourite(Builder $query): Builder
    {
        return $query->where('favourite', '=', 1);
    }
}
