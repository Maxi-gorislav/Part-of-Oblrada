<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The first name of user.
     *
     * @var string
     */
    public $name;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @param  string  $name
     */
    public function __construct($token, $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Unified AV password reset')
            ->greeting("Hi {$this->name},")
            ->line('There is a request to reset the password for your Unified AV account. To complete this password reset, please click the link below.')
            ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('This link expires in 24 hours. If you did not request this reset, please contact Unified AV customer support.');
    }
}
