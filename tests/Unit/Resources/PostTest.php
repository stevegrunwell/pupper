<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\Post as PostResource;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group HttpResources
 * @group Posts
 */
class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function to_array_should_format_the_creation_timestamp()
    {
        $post     = factory(Post::class)->create();
        $resource = (new PostResource($post))->toArray(null);

        $this->assertSame($post->created_at->toAtomString(), $resource['createdAt']);
    }
}
