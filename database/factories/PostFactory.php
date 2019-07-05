<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'id'      => $faker->uuid,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'content' => $faker->text(240),
    ];
});
