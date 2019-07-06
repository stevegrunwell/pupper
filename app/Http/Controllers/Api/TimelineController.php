<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TimelineController extends Controller
{
    /**
     * Display a user's timeline.
     */
    public function index(Request $request): ResourceCollection
    {
        $following = $request->user()->following()->select('id')->get();
        $posts     = Post::fromUsers($following)->get();

        return PostResource::collection($posts);
    }
}
