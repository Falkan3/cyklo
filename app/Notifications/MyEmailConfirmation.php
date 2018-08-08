<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class MyEmailConfirmation extends Notification
{
    use Queueable;

    /**
     * The email confirmation token.
     *
     * @var string
     */
    public $token;

    /**
     * The user's name
     *
     * @var string
     */
    public $name;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('emails/emailconfirmation.subject'))
                    ->greeting(__('emails/emailconfirmation.greeting', ['name' => $this->name]))
                    ->line(__('emails/emailconfirmation.introduction'))
                    ->action(__('emails/emailconfirmation.confirm'), url(\Lang::getLocale() . '/verify_email/' . $this->token))
                    ->line(__('emails/emailconfirmation.ending'))
                    ->salutation(__('emails/emailconfirmation.salutation'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
