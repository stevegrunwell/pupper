<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePost;
use App\Http\Resources\Post as PostResource;
use App\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Store a newly-created post.
     */
    public function store(CreatePost $request): JsonResponse
    {
        $post = $request->user()->posts()
            ->save(new Post($request->validated()));

        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json('', 204);
    }
}
