<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
