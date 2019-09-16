<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     *
     * For logged in users, this will be their timeline. For everyone else, this will be a
     * marketing page.
     */
    public function index(Request $request): Renderable
    {
        if ($request->user()) {
            $user = $request->user();
            $user->loadCount(['following', 'followers']);

            return view('timeline', [
                'recommendedUsers' => $user->getRecommendedUsers(3),
                'user'             => $user,
            ]);
        }

        return view('home');
    }
}
