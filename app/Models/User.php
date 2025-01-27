<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'facebook_link',
        'instagram_link',
        'x_link',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function travelPreferences(): BelongsToMany
    {
        return $this->belongsToMany(TravelPreference::class);
    }

    public function friendsAsSender(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'sender_id', 'receiver_id')
            ->withPivot('status');
    }

    public function friendsAsReceiver(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'receiver_id', 'sender_id')
            ->withPivot('status');
    }

    public function acceptedFriendsAsSender(): BelongsToMany
    {
        return $this->friendsAsSender()->wherePivot('status', FriendStatus::REQUEST_ACCEPTED);
    }

    public function acceptedFriendsAsReceiver(): BelongsToMany
    {
        return $this->friendsAsReceiver()->wherePivot('status', FriendStatus::REQUEST_ACCEPTED);
    }

    public function friends(): MergedRelation
    {
        return $this->mergedRelationWithModel(User::class, 'friends_view');
    }

    public function travels(): HasMany
    {
        return $this->hasMany(Travel::class);
    }

    public function getFriendshipStatus(): int
    {
        $user = auth()->user();
        $friend = Friend::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $this->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $this->id)
                    ->where('receiver_id', $user->id);
            })
            ->first();

        if ($friend === null) {
            return 0;
        } elseif ($friend->status === FriendStatus::REQUEST_ACCEPTED) {
            return 1;
        } elseif ($friend->sender_id === $user->id) {
            return 2;
        } else {
            return 3;
        }
    }

    public function getReceivedFriendRequestId(): ?int
    {
        $user = auth()->user();
        return Friend::where('sender_id', $this->id)
            ->where('receiver_id', $user->id)
            ->first()?->id;
    }

    public function getFriendsTravels(): Builder
    {
        $friendIds = $this->friends()->pluck('id');

        return Travel::query()
            ->with(['user'])
            ->whereIn('user_id', $friendIds)
            ->latest();
    }
}
