<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->name('api.')->group(function () {
    Route::get('posts/{post}', 'PostController@show')
        ->name('posts.show');

    // Routes that require authentication.
    Route::middleware('auth')->group(function () {
        Route::get('timeline', 'TimelineController@index')
            ->name('timeline');

        Route::apiResource('posts', 'PostController')
            ->except([
                'show',
                'update',
            ])
            ->names([
                'store'   => 'posts.store',
                'destroy' => 'posts.destroy',
            ]);

        // Following and unfollowing other users.
        Route::post('{user}/follow', 'UserController@follow')
            ->name('users.follow');
        Route::delete('{user}/follow', 'UserController@unfollow')
            ->name('users.unfollow');
    });

    Route::get('{user}/posts', 'TimelineController@user')
        ->name('userTimeline');
});
