<?php

namespace App\Console\Commands;

use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class GenerateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pupper:generate-users
                            {--earliest-date=1 year ago : The earliest sign-up date. This can be any Carbon-parsable string}
                            {--number=10                : The number of users to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate users with posts for testing purposes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $earliestDate = Carbon::parse($this->option('earliest-date'));
        $users        = $this->generateUsers($this->option('number'), $earliestDate);
        $headers      = ['ID', 'Username', '# of Posts'];
        $table        = $users->map(function ($user) {
            return [
                $user->id,
                $user->username,
                $user->posts->count(),
            ];
        });

        $this->info('Generated the following users:');
        $this->table($headers, $table);
    }

    /**
     * Generate a collection of users.
     */
    protected function generateUsers(int $number, Carbon $earliestDate): Collection
    {
        $creationDates = $this->getDatesInRange($earliestDate, $number);
        $users         = collect([]);

        foreach ($creationDates as $date) {
            $posts = $this->generatePosts(mt_rand(1, 100), $date);
            $user  = factory(User::class)->create([
                'created_at' => $date,
            ]);
            $user->posts()->saveMany($posts);

            $users->push($user);
        }

        return $users;
    }

    /**
     * Generate a collection of realistic-seeming posts.
     */
    protected function generatePosts(int $number, Carbon $earliestDate): array
    {
        $creationDates = $this->getDatesInRange($earliestDate, $number);
        $posts         = [];

        foreach ($creationDates as $date) {
            $posts[] = factory(Post::class)->make([
                'created_at' => $date,
            ]);
        }

        return $posts;
    }

    /**
     * Given a start date, randomly create $number timestamps between then and now.
     *
     * @param \Carbon\Carbon $startDate The earliest any date can be in the set.
     * @param int            $number    The number of dates to create.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getDatesInRange(Carbon $startDate, int $number): Collection
    {
        $timestamps = [];
        $min        = $startDate->timestamp;
        $max        = time();

        for ($i=0; $i<$number; $i++) {
            $timestamps[] = mt_rand($min, $max);
        }

        sort($timestamps, SORT_NUMERIC);

        return collect($timestamps)->map(function ($timestamp) {
            return Carbon::createFromTimestamp($timestamp);
        });
    }
}
