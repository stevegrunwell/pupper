<?php

namespace Tests\Unit\Services;

use App\Post;
use App\Services\PostParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

/**
 * @group Services
 */
class PostParserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider provide_valid_usernames()
     */
    public function it_should_detect_a_single_username($username)
    {
        $post = factory(Post::class)->make([
            'content' => sprintf('@%1$s can you see this?', $username),
        ]);

        $this->assertSame([
            $username
        ], (new PostParser($post))->getUsernames());
    }

    /**
     * @test
     */
    public function it_should_detect_multiple_usernames()
    {
        $usernames = Arr::flatten($this->provide_valid_usernames());
        $post      = factory(Post::class)->make([
            'content' => sprintf('#FollowFriday @%1$s', implode(' @', $usernames)),
        ]);

        $this->assertSame($usernames, (new PostParser($post))->getUsernames());
    }

    /**
     * @test
     */
    public function it_should_only_find_usernames_with_at_prefixes()
    {
        $post = factory(Post::class)->make([
            'content' => sprintf('Hey there, username'),
        ]);

        $this->assertEmpty((new PostParser($post))->getUsernames());
    }

    /**
     * @test
     */
    public function only_the_first_name_should_be_found_if_names_run_together()
    {
        $post = factory(Post::class)->make([
            'content' => sprintf('Great dogs: @lassie@benji'),
        ]);

        $this->assertSame(['lassie'], (new PostParser($post))->getUsernames());
    }

    /**
     * @test
     */
    public function pseudo_email_addresses_should_not_be_treated_as_usernames()
    {
        $post = factory(Post::class)->make([
            'content' => sprintf('Send me an email: test@example DOT com'),
        ]);

        $this->assertEmpty((new PostParser($post))->getUsernames());
    }

    /**
     * @test
     */
    public function usernames_shorter_than_4_characters_will_be_ignored()
    {
        $post = factory(Post::class)->make([
            'content' => sprintf('Hey @bob'),
        ]);

        $this->assertEmpty((new PostParser($post))->getUsernames());
    }

    /**
     * Provide an assortment of valid username patterns.
     */
    public function provide_valid_usernames(): array
    {
        return [
            'One word'        => ['myusername'],
            'Mixed-case'      => ['MyUsername'],
            'Underscores'     => ['my_username'],
            'Includes digits' => ['my_usern4m3'],
        ];
    }
}
