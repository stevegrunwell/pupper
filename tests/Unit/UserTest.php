<?php

namespace Tests\Unit;

use App\Post;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Users
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function usernames_must_be_unique()
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionCode(23505); // Unique violation.

        factory(User::class, 2)->create([
            'username' => 'someusername',
        ]);
    }

    /**
     * @test
     * @group Posts
     */
    public function users_can_have_many_posts()
    {
        $user = factory(User::class)->create();
        $user->posts()->saveMany(factory(Post::class, 3)->make());

        $this->assertCount(3, $user->posts);
    }
}
