<?php

namespace App\Listeners;

use App\Contracts\PostParserContract;
use App\Events\PostCreated;
use App\Notifications\MentionedInPost;
use App\User;
use Illuminate\Support\Facades\Notification;

class InspectNewPost
{
    /**
     * Handle the event.
     *
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $parser    = app()->makeWith(PostParserContract::class, [
            'post' => $event->post,
        ]);
        $usernames = $parser->getUsernames();

        if ($usernames) {
            Notification::send(
                User::whereIn('username', $usernames)
                    ->get(),
                new MentionedInPost($event->post)
            );
        }
    }
}
