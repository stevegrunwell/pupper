<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Scopes\ReverseChronologicalOrderScope;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show a user's profile and posts.
     */
    public function show(User $user): Renderable
    {
        return view('users.show')->with([
            'posts' => PostResource::collection($user->posts),
            'user'  => $user,
        ]);
    }

    /**
     * Show the accounts following the user.
     */
    public function followers(User $user): Renderable
    {
        return view('users.followers')->with([
            'user'  => $user,
            'users' => $user->followers()
                ->withCount(['followers', 'following', 'posts' => function (Builder $query) {
                    $query->withoutGlobalScope(ReverseChronologicalOrderScope::class);
                }])
                ->get(),
        ]);
    }

    /**
     * Show the accounts the user is following.
     */
    public function following(User $user): Renderable
    {
        return view('users.following')->with([
            'user'  => $user,
            'users' => $user->following()
                ->withCount(['followers', 'following', 'posts' => function (Builder $query) {
                    $query->withoutGlobalScope(ReverseChronologicalOrderScope::class);
                }])
                ->get(),
        ]);
    }

    /**
     * Show the user's notifications.
     */
    public function notifications(Request $request): Renderable
    {
        $user = $request->user();
        $notifications = $user->notifications;

        // Mark the unread notifications as read.
        $user->unreadNotifications->markAsRead();

        return view('users.notifications')->with([
            'notifications' => $notifications,
            'user'          => $user,
        ]);
    }
}
