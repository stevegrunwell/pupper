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

    /**
     * @test
     * @group Relationships
     */
    public function users_can_follow_other_users()
    {
        $users = factory(User::class, 2)->create();

        $users[0]->following()->save($users[1]);

        $this->assertCount(1, $users[0]->following);
        $this->assertTrue($users[0]->following()->first()->is($users[1]));
    }

    /**
     * @test
     * @group Relationships
     */
    public function users_can_be_followed_by_other_users()
    {
        $users = factory(User::class, 2)->create();

        $users[0]->following()->save($users[1]);

        $this->assertCount(1, $users[1]->followers);
        $this->assertTrue($users[1]->followers()->first()->is($users[0]));
    }

    /**
     * @test
     * @group Relationships
     */
    public function follows_will_indicate_if_a_user_follows_another_user()
    {
        $users = factory(User::class, 3)->create();
        $users[0]->following()->attach($users[1]->id);

        $this->assertTrue($users[0]->follows($users[1]));
        $this->assertFalse($users[0]->follows($users[2]));
    }

    /**
     * @test
     * @group Relationships
     */
    public function isFollowedBy_will_indicate_if_a_user_is_followed_by_another_user()
    {
        $users = factory(User::class, 3)->create();
        $users[0]->followers()->attach($users[1]->id);

        $this->assertTrue($users[0]->isFollowedBy($users[1]));
        $this->assertFalse($users[0]->isFollowedBy($users[2]));
    }

    /**
     * @test
     */
    public function getRecommendedUsers_should_suggest_relevant_users()
    {
        $this->markTestIncomplete();
        $user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function getRecommendedUsers_should_not_include_accounts_the_user_already_follows()
    {
        $user      = factory(User::class)->create();
        $following = $user->following()->saveMany(factory(User::class, 2)->make());
        $accounts  = factory(User::class, 2)->create();

        $recommendedIds = $user->getRecommendedUsers()->pluck('id');

        $this->assertEmpty($recommendedIds->intersect($user->following->pluck('id')));
        $this->assertCount(2, $recommendedIds->intersect($accounts->pluck('id')));
    }

    /**
     * @test
     */
    public function getRecommendedUsers_should_return_an_empty_collection_if_there_are_no_recommendations()
    {
        $user = factory(User::class)->create();
        $user->following()->saveMany(factory(User::class, 3)->make());

        $this->assertEmpty($user->getRecommendedUsers());
    }
}
