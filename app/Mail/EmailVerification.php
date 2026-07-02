<?php

namespace App\Mail;

use App\Model\BusinessSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $token;

    public function __construct($token = '')
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = $this->token;
        $logo = BusinessSetting::where(['key' => 'logo'])->value('value');

        return $this->view('email-templates.customer-email-verification', compact('token', 'logo'));
    }
}
