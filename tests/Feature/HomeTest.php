<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use MarkupAssertionsTrait,
        RefreshDatabase;

    /*
     * @todo Write tests around the existing business logic.
     *
     * - A guest should be shown the marketing homepage.
     * - A logged in user is shown their timeline.
     * - If a logged in user is not following anyone, they should be shown an onboarding screen.
     * - The onboarding screen should *not* be shown once the user follows someone.
     */

    /**
     * @test
     */
    public function a_guest_should_be_shown_the_marketing_homepage()
    {
        $this->get(route('home'))
            ->assertViewIs('home');
    }

    /**
     * @test
     */
    public function a_logged_in_user_is_shown_their_timeline()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertViewIs('timeline');
    }

    /**
     * @test
     */
    public function a_new_user_should_be_shown_the_onboarding_screen()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->get(route('home'));

        $this->assertContainsSelector('.onboarding-screen', $response->original);
    }

    /**
     * @test
     */
    public function the_onboarding_screen_should_not_be_shown_if_the_user_follows_someone()
    {
        $user = factory(User::class)->create();
        $user->following()
            ->saveMany(factory(User::class, 3)->make());

        $response = $this->actingAs($user)
            ->get(route('home'));

        $this->assertNotContainsSelector('.onboarding-screen', $response->original);
    }
}
