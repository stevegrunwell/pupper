<?php

namespace Tests\Unit;

use App\Events\PostCreated;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group Posts
 */
class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function posts_are_ordered_by_post_date_by_default()
    {
        $first  = factory(Post::class)->create([
            'created_at' => Carbon::now()->subDays(2),
        ]);
        $second = factory(Post::class)->create([
            'created_at' => Carbon::now()->subDays(1),
        ]);
        $third  = factory(Post::class)->create([
            'created_at' => Carbon::now(),
        ]);

        $this->assertSame([
            $third->id,
            $second->id,
            $first->id,
        ], Post::all()->pluck('id')->toArray());
    }

    /**
     * @test
     * @group Users
     */
    public function a_post_belongs_to_a_user()
    {
        $post = factory(Post::class)->make();

        $this->assertInstanceOf(User::class, $post->user);
    }

    /**
     * @test
     * @group Timeline
     * @covers App\Post::scopeFromUsers()
     */
    public function posts_can_be_filtered_by_users()
    {
        $users = factory(User::class, 3)->create()->each(function ($user) {
            $user->posts()->saveMany(factory(Post::class, 3)->make());
        });
        $users->pop();

        $postIds  = Post::fromUsers($users)->get()->pluck('id');
        $expected = $users->load('posts')->pluck('posts.*.id')->flatten();

        $this->assertCount(6, $postIds);
        $this->assertEquals($expected->sort(), $postIds->sort());
    }

    /**
     * @test
     * @group Events
     */
    public function a_PostCreated_event_should_be_fired_upon_post_creation()
    {
        Event::fake([
            PostCreated::class,
        ]);

        $post = factory(Post::class)->create();

        Event::assertDispatched(PostCreated::class, function ($event) use ($post) {
            return $event->post->is($post);
        });
    }

    /**
     * @test
     */
    public function a_post_can_have_many_replies()
    {
        $post    = factory(Post::class)->create();
        $replies = $post->replies()->saveMany(factory(Post::class, 3)->make());
        $post->refresh();

        $this->assertCount(3, $post->replies);
        $this->assertSame(
            $replies->pluck('id')->sort()->toArray(),
            $post->replies->pluck('id')->sort()->toArray()
        );
    }

    /**
     * @test
     */
    public function a_post_can_reference_its_parent_post()
    {
        $post  = factory(Post::class)->create();
        $reply = $post->replies()->save(factory(Post::class)->make());
        $post->refresh();

        $this->assertTrue($post->is($reply->parent));
    }
}
