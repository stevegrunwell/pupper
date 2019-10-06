<?php

namespace Tests\Feature;

use App\Notifications\MentionedInPost;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @group Notifications
 */
class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\User
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function a_user_should_be_able_to_see_their_notifications_page()
    {
        $this->actingAs($this->user)
            ->get(route('notifications'))
            ->assertViewIs('users.notifications');
    }

    /**
     * @test
     */
    public function a_user_should_be_notified_when_they_are_mentioned_in_a_post()
    {
        Notification::fake();

        $users = factory(User::class, 2)->create();

        $users[0]->posts()->save(factory(Post::class)->make([
            'content' => sprintf('Hey @%1$s, did you see this?', $users[1]->username),
        ]));

        Notification::assertSentTo($users[1], MentionedInPost::class);
    }

    /**
     * @test
     */
    public function notifications_should_be_marked_as_read_upon_first_load()
    {
        $this->markTestIncomplete();
    }
}
