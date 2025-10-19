<?php

namespace Modules\User\app\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('کد تأیید حساب کاربری')
            ->view('user::emails.otp')
            ->with([
                'verifyCode' => $this->code,
            ]);
    }
}
