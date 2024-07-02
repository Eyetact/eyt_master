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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $subject, $file) {
        $this->body = $body;
        $this->subject = $subject;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        if($this->file){
            return $this->subject($this->subject)
            ->markdown('emailTemplates.send_replay')
            ->with([
                'body' => $this->body
            ])
            ->attach(public_path($this->file));
        }else{
            return $this->subject($this->subject)
            ->markdown('emailTemplates.send_replay')
            ->with([
                'body' => $this->body
            ]);
        }
    }
}
