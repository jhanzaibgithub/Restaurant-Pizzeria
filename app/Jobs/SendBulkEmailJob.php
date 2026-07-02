<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscribers;

    public function __construct($subscribers)
    {
        $this->subscribers = $subscribers;
    }

    public function handle()
    {
        $subject = $this->subscribers['subject'];
        $emailMessage = $this->subscribers['message'];

        foreach ($this->subscribers['emails'] as $email) {
            Mail::send([], [], function ($message) use ($email, $subject, $emailMessage) {
                $message->to($email);
                $message->subject($subject);
                $message->html($emailMessage);
            });
        }
    }
}
