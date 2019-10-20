<?php

namespace Tests\Unit\Rules;

use App\Rules\Username;
use Tests\TestCase;

/**
 * @group Rules
 */
class UsernameTest extends TestCase
{
    /**
     * @todo Write some tests in here!
     */

    /**
     * Shortcut for validating a username against the Username rule.
     *
     * @param string $username The username to validate.
     *
     * @return bool
     */
    protected function validateUsername(string $username): bool
    {
        return (new Username)->passes('username', $username);
    }
}
