<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TimelineController extends Controller
{
    /**
     * Display posts from accounts being followed by the current user.
     */
    public function index(Request $request): ResourceCollection
    {
        $includedUsers = $request->user()->following()->select('id')->get()
            ->whereNotIn(User::whereNotIn($request->user()->following->pluck('id')))
            ->push($request->user()); // Include the current user's posts.
        $posts     = Post::fromUsers($includedUsers)->paginate();

        return PostResource::collection($posts);
    }

    /**
     * Show posts from a single user.
     */
    public function user(User $user, Request $request): ResourceCollection
    {
        return PostResource::collection($user->posts()->paginate());
    }
}
