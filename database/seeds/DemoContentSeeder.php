<?php

use App\Post;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DemoContentSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $testData = [
        [
            'username'     => 'ScoobyDoo',
            'display_name' => 'Scooby Doo',
            'email'        => 'scoobydoo@example.com',
            'posts'        => [
                [
                    'content' =>  'Ruh-roh!',
                ],
                [
                    'content' => 'Just discovered CostCo has Scooby Snacks. This could be dangerous!',
                ],
                [
                    'content' => '@lassie Wanna grab pizza sometime?',
                ],
            ],
        ],
        [
            'username'     => 'lassie',
            'display_name' => '745513',
            'email'        => 'lassie@example.com',
            'posts'        => [
                [
                    'content' =>  'Ugh, Timmy fell down the well again!',
                ],
            ],
        ],
        [
            'username'     => 'bobama',
            'display_name' => 'Bo',
            'email'        => 'bo@example.com',
            'posts'        => [
                [
                    'content' =>  'Went on a walk past my old house today, and it smells like rotting hamberders. What the heck happened?',
                ],
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->testData as $user) {
            $userRecord = User::firstOrCreate([
                'username' => $user['username'],
            ], array_merge([
                'password' => 'secret',
            ], Arr::except($user, ['posts'])));

            $userRecord->posts()->createMany($user['posts']);
        }
    }
}
