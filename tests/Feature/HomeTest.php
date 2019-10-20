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
     * @todo Write tests around the existing business logic.
     *
     * - A guest should be shown the marketing homepage.
     * - A logged in user is shown their timeline.
     * - If a logged in user is not following anyone, they should be shown an onboarding screen.
     * - The onboarding screen should *not* be shown once the user follows someone.
     */
}
