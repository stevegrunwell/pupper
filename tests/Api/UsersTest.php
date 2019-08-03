<?php

namespace Tests\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Users
 */
class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group Relationships
     */
    public function a_user_can_follow_another_user()
    {
        $users = factory(User::class, 2)->create();

        $this->actingAs($users[0])
            ->post(route('api.users.follow', ['user' => $users[1]]));

        $this->assertCount(1, $users[1]->followers);
    }

    /**
     * @test
     * @group Relationships
     */
    public function nothing_happens_when_following_an_already_followed_user()
    {
        $users = factory(User::class, 2)->create();
        $users[1]->followers()->save($users[0]);

        $this->actingAs($users[0])
            ->post(route('api.users.follow', ['user' => $users[1]]));

        $this->assertCount(1, $users[1]->followers);
    }

    /**
     * @test
     * @group Relationships
     */
    public function a_user_cannot_follow_themselves()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('api.users.follow', ['user' => $user]))
            ->assertForbidden();
    }

    /**
     * @test
     * @group Relationships
     */
    public function a_user_can_unfollow_another_user()
    {
        $users = factory(User::class, 2)->create();
        $users[1]->followers()->save($users[0]);

        $this->actingAs($users[0])
            ->delete(route('api.users.follow', ['user' => $users[1]]));

        $this->assertCount(0, $users[1]->followers);
    }

    /**
     * @test
     * @group Relationships
     */
    public function nothing_happens_when_unfollowing_an_already_unfollowed_user()
    {
        $users = factory(User::class, 2)->create();

        $this->actingAs($users[0])
            ->delete(route('api.users.follow', ['user' => $users[1]]));

        $this->assertCount(0, $users[0]->followers);
    }
}
