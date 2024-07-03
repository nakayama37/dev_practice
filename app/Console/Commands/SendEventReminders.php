<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Event;
use App\Mail\EventReminderMail;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

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
    protected $description = 'Send reminder emails and LINE notifications for upcoming events';

    /**
     * イベントリマインダーの実行
     *
     * @return void
     */
    public function handle()
    {
        // Line bot の設定
        $httpClient = new CurlHTTPClient(config('services.line.messaging_api_channel_token'));
        $lineBot = new LINEBot($httpClient, ['channelSecret' => config('services.line.messaging_api_channel_secret')]);

        //翌日のイベント取得 
        $events = Event::where('start_at', '>=', Carbon::now())
            ->where('start_at', '<=', Carbon::now()->addDays(1))
            ->where('is_public', true)
            ->get();

        if ($events) {
            foreach ($events as $event) {
                foreach ($event->users as $user) {
                    // Send email
                    Mail::to($user->email)->send(new EventReminderMail($event));
                    $this->info('リマインダーメールを送信しました: ' . $user->email);

                    // lineIDがあればLine通知を行う
                    if ($user->line_id) {
                        $message = new TextMessageBuilder('イベントのリマインダー: ' . $event->title . ' がまもなく開始されます。');
                        $response = $lineBot->pushMessage($user->line_id, $message);

                        if ($response->isSucceeded()) {
                            $this->info('LINEリマインダーを送信しました: ' . $user->line_id);
                        } else {
                            $this->error('LINEリマインダーの送信に失敗しました: ' . $response->getRawBody());
                        }
                    }
                }
            }
        } else {
            $this->info('翌日のイベントはありません');
        }

        return;
    }
}
