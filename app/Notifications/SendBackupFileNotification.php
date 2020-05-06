<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendBackupFileNotification extends Notification
{
    use Queueable;
    private $databasePath;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($databasePath)
    {
        //
        $this->databasePath = $databasePath;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
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
            ->subject(env('APP_NAME'))
            ->line(env('APP_NAME').' Databae backup')
            ->line('Backup date: '.Carbon::now()->format('D d/m/y H:i:s'))
            ->attach($this->databasePath);
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
