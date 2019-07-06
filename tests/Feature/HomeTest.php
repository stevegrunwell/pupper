<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class HomeTest extends TestCase
{
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
        $this->actingAs(factory(User::class)->make())
            ->get(route('home'))
            ->assertViewIs('timeline');
    }
}
