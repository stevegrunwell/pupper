<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication routes.
Auth::routes();

// Homepage.
Route::get('/', 'HomeController@index')
    ->name('home');

// Routes only available to logged-in users.
Route::middleware(['auth'])->group(function () {

    // Creating new posts.
    Route::resource('barks', 'PostController')->except([
        'index',
        'show', // Excluded since posts.show is defined elsewhere.
        'edit',
        'update',
    ])
    ->names([
        'create' => 'posts.create',
        'store'  => 'posts.store',
    ]);

});

// All users can see posts.
Route::get('barks/{post}', 'PostController@show')
    ->name('posts.show');

// Registering last to ensure usernames can't hijack other routes.
Route::get('{user}', 'UserController@show')
    ->name('users.show');
