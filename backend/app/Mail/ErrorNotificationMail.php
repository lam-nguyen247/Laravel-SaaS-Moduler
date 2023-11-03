<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorNotificationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Body
     *
     * @var string
     */
    private string $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\Throwable $throwable)
    {
        $this->body = $throwable->getMessage() . PHP_EOL
            . $throwable->getFile() . ':' . $throwable->getLine() . PHP_EOL
            . $throwable->getTraceAsString();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $subject = '【' . config('app.name') . '】'
            . '＜' . config('app.url') . '＞'
            . config('const.mail.subject.system_error');

        return $this
            ->to(config('exception.notification_mail.to'))
            ->subject($subject)
            ->view('mails.error')
            ->with(['body' => $this->body]);
    }
}
