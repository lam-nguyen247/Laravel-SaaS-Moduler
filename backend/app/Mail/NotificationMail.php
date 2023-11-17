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
    use Queueable;
    use SerializesModels;

    /**
     * @var mixed $mailFrom
     */
    public mixed $mailFrom;

    /**
     * @var mixed $mailView
     */
    public mixed $mailView;

    /**
     * @var mixed $params
     */
    public mixed $params;

    /**
     * @var mixed $mailSubject
     */
    public mixed $mailSubject;

    /**
     * Create a new message instance.
     *
     * @param  mixed $mailView
     * @param  mixed $mailSubject
     * @param  mixed $params
     * @return void
     */
    public function __construct(mixed $mailView, mixed $mailSubject, mixed $params)
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
