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
        '<a href="%1$s" class="username text-muted">@%2$s</a>',
        route('users.show', ['user' => $user]),
        $user->username
    );
}
