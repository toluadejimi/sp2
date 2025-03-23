<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ReceiverMail;
use Mail;

class SendReceiverMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailSettings = Setting::first();
        if ($emailSettings) {
            Config::set('mail.mailers.smtp.host', $emailSettings->mail_host);
            Config::set('mail.mailers.smtp.port', $emailSettings->mail_port);
            Config::set('mail.mailers.smtp.encryption', $emailSettings->mail_encryption);
            Config::set('mail.mailers.smtp.username', $emailSettings->mail_username);
            Config::set('mail.mailers.smtp.password', $emailSettings->mail_password);
            Config::set('mail.from.address', $emailSettings->mail_from_address);
            Config::set('mail.from.name', $emailSettings->mail_from_name);
        }
        $email = new ReceiverMail();
        Mail::to($this->details['email'])->send($email);
    }
}
