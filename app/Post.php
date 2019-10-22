<?php

namespace App;

use App\Events\PostCreated;
use App\Scopes\ReverseChronologicalOrderScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => PostCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'parent_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'string',
        'parent_id' => 'string',
    ];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Lower the number of results per page.
     *
     * @var int
     */
    protected $perPage = 20;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ReverseChronologicalOrderScope);
    }

    /**
     * A post belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A post can have many replies.
     */
    public function replies()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    /**
     * A post can be in reply to another post.
     */
    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    /**
     * Limit posts to those created by specific users.
     */
    public function scopeFromUsers(Builder $query, Collection $users): Builder
    {
        return $query->whereIn('user_id', $users->pluck('id')->toArray());
    }

    /**
     * Filter out posts that are replies to users we don't follow.
     */
    public function scopeNotRepliesToStrangers(): Builder
    {

    }
}
