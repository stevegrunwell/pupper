<?php

namespace Tests\Unit\Listeners;

use App\Contracts\PostParserContract;
use App\Events\PostCreated;
use App\Listeners\InspectNewPost;
use App\Notifications\MentionedInPost;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Tests\TestCase;

/**
 * @group Listeners
 * @group Notifications
 */
class InspectNewPostTest extends TestCase
{
    use RefreshDatabase,
        WithoutEvents;

    /**
     * @var \App\Contracts\PostParserContract
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = Mockery::mock(PostParserContract::class);

        $this->app->extend(PostParserContract::class, function ($service) {
            return $this->parser;
        });
    }

    /**
     * @test
     */
    public function it_should_notify_a_user_when_they_are_mentioned_in_a_post()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $this->parser->shouldReceive('getUsernames')
            ->once()
            ->andReturn([$user->username]);

        $this->handle();

        Notification::assertSentTo($user, MentionedInPost::class);
    }

    /**
     * @test
     */
    public function it_should_notify_all_users_mentioned_in_a_post()
    {
        Notification::fake();

        $users = factory(User::class, 3)->create();

        $this->parser->shouldReceive('getUsernames')
            ->once()
            ->andReturn($users->pluck('username')->toArray());

        $this->handle();

        Notification::assertSentTo($users, MentionedInPost::class);
    }

    /**
     * @test
     */
    public function it_should_verify_that_a_user_exists_before_attempting_to_notify_them()
    {
        Notification::fake();

        $this->parser->shouldReceive('getUsernames')
            ->once()
            ->andReturn(['someuser']);

        $this->handle();

        Notification::assertNothingSent();
    }

    /**
     * @test
     * @ticket https://example.com
     */
    public function a_user_should_not_be_notified_about_their_own_posts()
    {
        Notification::fake();

        $post = factory(Post::class)->create();

        $this->parser->shouldReceive('getUsernames')
            ->andReturn([$post->user->username]);

        $this->handle($post);

        Notification::assertNothingSent();
    }

    /**
     * Invoke the handle() method.
     */
    protected function handle(Post $post = null)
    {
        $event = new PostCreated($post ?: factory(Post::class)->make());

        (new InspectNewPost)->handle($event);
    }
}
