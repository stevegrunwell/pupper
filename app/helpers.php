<?php
/**
 * Helper functions for Pupper.
 */

use App\User;

/**
 * Prefix a username with "@" and add data-attributes for the user.
 *
 * @param \App\User $user The user to render.
 */
function username(User $user): string
{
    return sprintf(
        '<span class="username text-muted" data-profile-url="%1$s">@%2$s</span>',
        route('users.show', ['user' => $user]),
        $user->username
    );
}
