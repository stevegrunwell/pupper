<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * Follow a user.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function follow(User $user, Request $request): JsonResponse
    {
        // A user cannot follow themselves.
        if ($user->id === $request->user()->id) {
            throw new HttpException(403, 'Users may not follow themselves');
        }

        if (! $request->user()->follows($user)) {
            $request->user()->following()->attach($user->id);
        }

        return response()->json([], 204);
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user, Request $request): JsonResponse
    {
        if ($request->user()->follows($user)) {
            $request->user()->following()->detach($user->id);
        }

        return response()->json([], 204);
    }
}
