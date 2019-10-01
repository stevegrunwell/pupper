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
     * @testWith ["myusername"]
     *           ["my_username"]
     *           ["myUsername"]
     *           ["myusername123"]
     *           ["_my_username_"]
     */
    public function test_with_valid_names(string $username)
    {
        $this->assertTrue($this->validateUsername($username));
    }

    /**
     * @test
     */
    public function usernames_may_not_contain_spaces()
    {
        $this->assertFalse($this->validateUsername('user name'));
    }

    /**
     * @test
     */
    public function usernames_may_not_contain_the_at_symbol()
    {
        $this->assertFalse($this->validateUsername('@username'));
    }

    /**
     * @test
     * @testWith ["#username"]
     *           ["<username>"]
     *           ["username!"]
     */
    public function usernames_may_not_contain_other_special_characters(string $username)
    {
        $this->assertFalse($this->validateUsername($username));
    }

    /**
     * @test
     */
    public function usernames_may_not_begin_with_digits()
    {
        $this->assertFalse($this->validateUsername('123username'));
    }

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
