<?php

namespace Tests\Api;

use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Posts
 * @group Timeline
 */
class TimelineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $following;

    /**
     * @var \App\User
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user      = factory(User::class)->create();
        $this->following = $this->user->following()->saveMany(factory(User::class, 2)->make());
    }

    /**
     * @test
     */
    public function the_timeline_should_consist_of_posts()
    {
        $this->following->each(function ($user) {
            $user->posts()->saveMany(factory(Post::class, 2)->make());
        });

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', route('api.timeline'))
            ->assertJsonCount(4, 'data');
    }

    /**
     * @test
     */
    public function posts_should_be_in_reverse_chronological_order()
    {
        $earliest = $this->following[0]->posts()->save(factory(Post::class)->make([
            'content'    => 'This came earlier',
            'created_at' => Carbon::now()->subDays(1),
        ]));
        $latest   = $this->following[0]->posts()->save(factory(Post::class)->make([
            'content'    => 'This is most recent',
            'created_at' => Carbon::now(),
        ]));

        $response = $this->actingAs($this->user, 'api')
            ->get(route('api.timeline'));

        $this->assertSame([
            $latest->id,
            $earliest->id,
        ], $response->original->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function only_posts_from_users_that_are_being_followed_should_be_included()
    {
        $this->following[0]->posts()->saveMany(factory(Post::class, 3)->make());
        $post = factory(Post::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->get(route('api.timeline'))
            ->assertJsonMissing([
                'id' => $post->id,
            ]);
    }

    /**
     * @test
     */
    public function a_users_own_posts_should_be_included_in_their_timeline()
    {
        $post = $this->user->posts()->save(factory(Post::class)->make());

        $response = $this->actingAs($this->user, 'api')
            ->get(route('api.timeline'))
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_should_not_see_replies_unless_they_are_following_the_recipient()
    {
        $stranger     = factory(User::class)->create();
        $strangerPost = $stranger->posts()->save(factory(Post::class)->make());
        $post         = $this->user->following->first()
            ->posts()->save(factory(Post::class)->make([
                'parent_id' => $strangerPost->id,
                'content'   => '@' . $stranger->username . ' This is my reply',
            ]));

        $response = $this->actingAs($this->user, 'api')
            ->get(route('api.timeline'))
            ->assertJsonMissing([
                'id' => $post->id,
            ]);
    }

    /**
     * @test
     */
    public function a_user_timeline_should_only_include_posts_from_the_user()
    {
        $ids = $this->following[0]->posts()->saveMany(factory(Post::class, 3)->make());
        $this->following[1]->posts()->saveMany(factory(Post::class, 2)->make());

        $response = $this->json('get', route('api.userTimeline', ['user' => $this->following[0]]));

        $response->assertJsonCount(3, 'data');
        $this->assertEquals(
            $ids->pluck('id')->sort(),
            $response->original->pluck('id')->sort()
        );
    }
}
