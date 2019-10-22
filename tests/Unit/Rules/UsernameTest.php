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
     * @test
     * @testWith ["stevegrunwell"]
     *           ["steve123"]
     */
    public function it_should_accept_alphanumeric_characters($username)
    {
        $this->assertTrue($this->validateUsername($username));
    }

    public function provide_cool_usernames()
    {
        return [
            'All letters' => ['stevegrunwell'],
            'Mix of letters and numbers' => ['steve123'],
            'Start with an underscore' => ['_foo'],
        ];
    }

    /**
     * @test
     */
    public function usernames_cannot_begin_with_digits()
    {
        $this->assertFalse($this->validateUsername('123foobar'));
    }

    /**
     * @test
     */
    public function usernames_cannot_be_a_single_character()
    {
        $this->assertFalse($this->validateUsername('a'));
    }

    /**
     * @test
     */
    public function we_should_get_our_custom_validation_message()
    {
        $this->assertSame(trans('validation.username'), (new Username)->message());
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
