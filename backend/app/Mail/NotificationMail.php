<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/*
 * お客様への通知メール操作クラス
 */

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var from
     */
    public $mailFrom;

    /**
     * @var view
     */
    public $mailView;

    /**
     * @var params
     */
    public $params;

    /**
     * @var subject
     */
    public $mailSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     * @param mixed $mailView
     * @param mixed $mailSubject
     * @param mixed $params
     */
    public function __construct($mailView, $mailSubject, $params)
    {
        $this->mailFrom = config('mail.from.address');
        $this->mailView = $mailView;
        $this->params = $params;

        $this->mailSubject = $mailSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->mailFrom)
            ->subject($this->mailSubject)
            ->with($this->params)
            ->view('mails.' . $this->mailView);
    }
}
