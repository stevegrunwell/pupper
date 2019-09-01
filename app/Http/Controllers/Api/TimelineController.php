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
        $following = $request->user()->following()->select('id')->get()
            ->push($request->user()); // Include the current user's posts.
        $posts     = Post::fromUsers($following)->get();

        return PostResource::collection($posts);
    }

    /**
     * Show posts from a single user.
     */
    public function user(User $user, Request $request): ResourceCollection
    {
        return PostResource::collection($user->posts);
    }
}
