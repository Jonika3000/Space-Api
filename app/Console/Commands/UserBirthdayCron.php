<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\UserBirthdayNotification;
use Illuminate\Console\Command;

class UserBirthdayCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-birthday:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check today users birthdays';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $today = date('m-d');

        foreach ($users as $user) {
            $birthday =  date('m-d', strtotime($user->birthday));
            if ($birthday === $today) {
                $user->notify(new UserBirthdayNotification($user));
            }
        }
    }
}
