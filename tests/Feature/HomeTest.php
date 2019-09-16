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

    /**
     * @test
     */
    public function a_guest_is_shown_the_marketing_homepage()
    {
        $this->get(route('home'))
            ->assertViewIs('home');
    }

    /**
     * @test
     */
    public function a_logged_in_user_is_shown_their_timeline()
    {
        $this->actingAs(factory(User::class)->create())
            ->get(route('home'))
            ->assertViewIs('timeline');
    }

    /**
     * @test
     */
    public function a_user_that_is_not_following_anyone_should_be_shown_an_onboarding_screen()
    {
        $response = $this->actingAs(factory(User::class)->create())
            ->get(route('home'));

        $this->assertContainsSelector('.jumbotron', $response->original);
    }

    /**
     * @test
     */
    public function the_onboarding_screen_should_not_be_shown_once_the_user_follows_someone()
    {
        $user = factory(User::class)->create();
        $user->following()->saveMany(factory(User::class, 2)->make());

        $response = $this->actingAs($user)
            ->get(route('home'));

        $this->assertNotContainsSelector('.jumbotron', $response->original);
    }
}
