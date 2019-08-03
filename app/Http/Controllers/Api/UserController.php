<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Post;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * Follow a user.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function follow(User $user, Request $request): RedirectResponse
    {
        // A user cannot follow themselves.
        if ($user->id === $request->user()->id) {
            throw new HttpException(403, 'Users may not follow themselves');
        }

        if (! $request->user()->follows($user)) {
            $request->user()->following()->attach($user->id);
        }

        return redirect(route('users.show', ['user' => $user]));
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user, Request $request): RedirectResponse
    {
        if ($request->user()->follows($user)) {
            $request->user()->following()->detach($user->id);
        }

        return redirect(route('users.show', ['user' => $user]));
    }
}
