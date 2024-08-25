<?php

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\UserBirthdayNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MailTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_send_birthday_mail(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->notify(new UserBirthdayNotification($user));

        Notification::assertSentTo(
            [$user],
            UserBirthdayNotification::class
        );
    }
}
