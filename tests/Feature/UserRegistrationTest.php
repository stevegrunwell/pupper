<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * @group Users
 */
class UserRegistrationTest extends TestCase
{
    /**
     * @test
     */
    public function a_guest_can_see_the_registration_screen()
    {
        $this->get(route('register'))
            ->assertViewIs('auth.register');
    }

    /**
     * @test
     */
    public function a_logged_in_user_is_redirected_to_the_homepage_from_the_registration_screen()
    {
        $this->actingAs(factory(User::class)->make())
            ->get(route('register'))
            ->assertRedirect(route('home'));
    }

    /**
     * @test
     * @covers App\Http\Controllers\Auth\RegisterController
     */
    public function a_new_user_can_be_registered()
    {
        $user = factory(User::class)->make();

        $response = $this->post(route('register'), [
            'username'              => $user->username,
            'display_name'          => $user->display_name,
            'email'                 => $user->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('home'));

        // The user should automatically be logged in.
        $this->assertAuthenticatedAs(User::where('username', $user->username)->first());
    }
}
