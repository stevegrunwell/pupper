<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')
    ->namespace('Api')
    ->name('api.')
    ->group(function () {
        Route::apiResource('posts', 'PostController')
            ->except([
                'update',
            ])
            ->names([
                'store'   => 'posts.store',
                'update'  => 'posts.update',
                'destroy' => 'posts.destroy',
            ]);
    });
