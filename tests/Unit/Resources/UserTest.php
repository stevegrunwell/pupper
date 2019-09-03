<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group HttpResources
 * @group Users
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_user_resource_should_contain_avatars_at_different_sizes()
    {
        $user     = factory(User::class)->create();
        $resource = (new UserResource($user))->toArray(null);

        $this->assertArrayHasKey('avatar', $resource);
        $this->assertArrayHasKey('thumbnail', $resource['avatar']);
        $this->assertArrayHasKey('large', $resource['avatar']);
    }
}
