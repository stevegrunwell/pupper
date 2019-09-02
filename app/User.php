<?php

namespace App;

use Gravatar\Gravatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'display_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'string',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * A user can have any number of posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * A user can follow other users.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'user_follows', 'user_id', 'target_id')
            ->as('accounts')
            ->withTimestamps();
    }

    /**
     * A user can be followed.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'user_follows', 'target_id', 'user_id')
            ->as('accounts');
    }

    /**
     * Determine if this user follow the given $user.
     */
    public function follows(User $user): bool
    {
        return $this->following()->where('id', $user->id)->exists();
    }

    /**
     * Determine if this user is followed by the given $user.
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('id', $user->id)->exists();
    }

    /**
     * Retrieve the URL for a user's Gravatar.
     *
     * @param int $size The size (in pixels) of the Gravatar. Default is 200.
     *
     * @return string The URL to the user's Gravatar.
     */
    public function getAvatarUrl(int $size = 200): string
    {
        return (new Gravatar([
            's' => $size,
            'd' => 'wavatar',
        ], true))->avatar($this->email);
    }

    /**
     * Get a selection of users to recommend to the user.
     *
     * Since this app is just a demo, this is very unsophisticated: basically, find some number
     * of users the current user does not yet follow.
     *
     * @param int $limit The maximum number of accounts to return.
     */
    public function getRecommendedUsers(int $limit = 3): Collection
    {
        return User::limit($limit)
            ->whereDoesntHave('followers', function (Builder $query) {
                $query->where('user_id', $this->id);
            })
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->get();
    }
}
