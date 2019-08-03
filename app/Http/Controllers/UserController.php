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
}
