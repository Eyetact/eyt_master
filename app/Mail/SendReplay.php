<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReplay extends Mailable
{
    use Queueable, SerializesModels;

    public $body, $subject, $file;
    public $fromEmail, $fromName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $subject, $file, $fromEmail, $fromName) {
        $this->body = $body;
        $this->subject = $subject;
        $this->file = $file;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $mail = $this->subject($this->subject)
                        ->markdown('emailTemplates.send_replay')
                        ->with('body', $this->body);

        if ($this->file) {
            $mail->attach(public_path($this->file));
        }

        $mail->from($this->fromEmail, $this->fromName);

        return $mail;
    }
}
