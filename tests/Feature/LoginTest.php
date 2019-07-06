<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Auth
 * @group Users
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function login_is_based_on_the_username()
    {
        $user = factory(User::class)->create();

        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
