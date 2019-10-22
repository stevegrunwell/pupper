<?php

namespace Tests\Unit;

use App\Http\Requests\CreatePost;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use MohammedManssour\FormRequestTester\TestsFormRequests;
use Tests\TestCase;

/**
 * @group Posts
 * @group Requests
 * @covers \App\Http\Requests\CreatePost
 */
class CreatePostTest extends TestCase
{
    use TestsFormRequests,
        RefreshDatabase;

    /**
     * @test
     * phpcs:disable Generic.Files.LineLength.TooLong
     * @testWith [null]
     *           [""]
     *           ["This is a very long string that exceeds the 240 character limit that have been put on message bodies. We don't want someone to write a book here, this is meant to be a short, 240-character or less post. Want something longer? Go write a blog, please!"]
     * phpcs:enable Generic.Files.LineLength.TooLong
     */
    public function test_post_content_validation($value)
    {
        $response = $this->formRequest(CreatePost::class, [
            'content' => $value,
        ])
            ->assertValidationErrors('content');
    }

    /**
     * @test
     */
    public function a_parent_id_must_be_a_valid_uuid()
    {
        $parent = factory(Post::class)->create();

        $response = $this->formRequest(CreatePost::class, [
            'parent_id' => $parent->id,
        ])
            ->assertValidationErrorsMissing(['parent_id']);
    }

    /**
     * @test
     */
    public function a_parent_id_must_be_another_post()
    {
        $id = (string) Str::uuid();

        $response = $this->formRequest(CreatePost::class, [
            'parent_id' => $id,
        ])
            ->assertValidationErrors(['parent_id']);
    }
}
