<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Mail\EventReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for upcoming events';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events = Event::where('start_at', '>=', Carbon::now())
            ->where('start_at', '<=', Carbon::now()->addDays(1))
            ->get();

        foreach ($events as $event) {
            foreach ($event->users as $user) {
                Mail::to($user->email)->send(new EventReminderMail($event));
                $this->info('リマインダーメールを送信しました: ' . $user->email);
            }
        }

        return 0;
    }
}
