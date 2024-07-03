<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $qr_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $user, $qr_code)
    {
        $this->event = $event;
        $this->user = $user;
        $this->qr_code = $qr_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('イベント参加登録確認')
        ->view('emails.event_registration_confirmation');
    }
}
