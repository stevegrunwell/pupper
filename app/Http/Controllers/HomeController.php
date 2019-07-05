<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     *
     * For logged in users, this will be their timeline. For everyone else, this will be a
     * marketing page.
     */
    public function index(): Renderable
    {
        if (Auth::check()) {
            return view('timeline')->with([
                'posts' => PostResource::collection(Post::all()),
            ]);
        }

        return view('home');
    }
}
