<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}
