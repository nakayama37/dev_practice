<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $addFriendUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password,$addFriendUrl)
    {
        $this->user = $user;
        $this->password = $password;
        $this->addFriendUrl = $addFriendUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('ユーザー登録確認')
        ->view('emails.registration_confirmation');
    }
}
