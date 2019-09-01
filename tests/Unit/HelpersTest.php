<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

/**
 * @group Helpers
 */
class HelpersTest extends TestCase
{
    /**
     * @test
     * @covers username()
     */
    public function username_should_prepend_an_at_sign()
    {
        $user = factory(User::class)->make();

        $this->assertStringContainsString(
            '@' . $user->username,
            username($user)
        );
    }
}
