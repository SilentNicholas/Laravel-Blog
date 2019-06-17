<?php

namespace App\Mail;

use App\Subscriptions;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SubscribeEmail
 * @package App\Mail
 */
class SubscribeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Subscriptions
     */
    public $sub;

    /**
     * Create a new message instance.
     *
     * @param $subscriber
     */
    public function __construct(Subscriptions $subscriber)
    {
        $this->sub = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.verify');
    }
}
