<?php

namespace App\Notifications;

use App\Http\Resources\Post as PostResource;
use App\Post;
use Illuminate\Notifications\Notification;

class MentionedInPost extends Notification
{
    /**
     * @var \App\Post
     */
    public $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'post' => new PostResource($this->post),
            'type' => 'mentioned',
        ];
    }
}
