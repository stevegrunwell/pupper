<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
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
            return view('timeline');
        }

        return view('home');
    }
}
