<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The view to use for the message.
     *
     * @var string
     */
    public $view;

    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;

    /**
     * body
     *
     * @var string
     */
    private string $body;

    /**
     * to Address
     *
     * @var string
     */
    private string $toAddress;

    /**
     * Create a new message instance.
     *
     * @param string $view
     * @param string $subject
     * @param string $body
     * @param string $to
     */
    public function __construct(string $view, string $subject, string $body, string $to)
    {
        $this->view = $view;
        $this->subject = '【' . config('app.name') . '】'
            . '＜' . config('app.url') . '＞'
            . $subject;
        $this->body = $body;
        $this->toAddress = $to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->from)
            ->to($this->toAddress)
            ->subject($this->subject)
            ->view('mails.' . $this->view)
            ->with(['body' => $this->body]);
    }
}
