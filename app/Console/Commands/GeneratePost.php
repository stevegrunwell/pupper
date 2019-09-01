<?php

namespace App\Console\Commands;

use App\Post;
use App\User;
use Illuminate\Console\Command;

class GeneratePost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pupper:generate-post
                            {username   : The username of the post author}
                            {--content= : The body of the post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new post for a user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::where('username', $this->argument('username'))->first();

        if (! $user) {
            $this->error(sprintf('User "%s" was not found, aborting.', $this->argument('username')));
            exit(1);
        }

        $args = [];

        if ($this->option('content')) {
            $args['content'] = $this->option('content');
        }

        $post = $user->posts()->save(factory(Post::class)->make($args));

        $this->info('New Bark published for @' . $user->username . ':');
        $this->comment('> ' . $post->content);
    }
}
