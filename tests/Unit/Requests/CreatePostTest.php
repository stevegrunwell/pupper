<?php

namespace Tests\Unit;

use App\Http\Requests\CreatePost;
use MohammedManssour\FormRequestTester\TestsFormRequests;
use Tests\TestCase;

/**
 * @group Posts
 * @group Requests
 * @covers \App\Http\Requests\CreatePost
 */
class CreatePostTest extends TestCase
{
    use TestsFormRequests;

    /**
     * phpcs:disable Generic.Files.LineLength.TooLong
     * @test
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
}
