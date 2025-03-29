<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Enum\NotificationType;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;

//        for make notifications
//        $user = \Auth::user();
//        $message = "custom message here";
//
//        $user->notify(new UserNotification(
//            message: $message //which you want to send

//        ));

class UserNotification extends Notification
{
    use Queueable;

    protected $from;

    protected $owner;

    protected $subject;

    protected $message;

    protected $actionText;

    protected $type; //Enums -> Notification Type

    protected $actionUrl;

    protected $channels; // for pass only email or store notification to database use ['mail', 'database'], for both email and database

    /**
     * Create a new notification instance.
     */
    public function __construct(
        $from = 'Bjjsourceinfo@gmail.com',
        $owner = 'Adam Leddy',
        $subject = 'New Notification',
        $message = null,
        $actionText = 'Dashboard',
        $actionUrl = '/',
        $channels = ['mail', 'database'],
        $type = NotificationType::INFO
    )
    {
        $this->from = $from;
        $this->owner = $owner;
        $this->subject = $subject;
        $this->message = $message;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
        $this->channels = $channels;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new NotificationMail(
            $this->from,
            $this->owner,
            $this->subject,
            $this->message,
            $this->actionText,
            url($this->actionUrl),
            $notifiable
        ))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        Log::info([
            'from' => $this->from,
            'owner' => $this->owner,
            'subject' => $this->subject,
            'message' => $this->message,
            'actionText' => $this->actionText,
            'actionUrl' => $this->actionUrl,
            'type' => $this->type,
        ]);
        return [
            'from' => $this->from,
            'owner' => $this->owner,
            'subject' => $this->subject,
            'message' => $this->message,
            'actionText' => $this->actionText,
            'actionUrl' => $this->actionUrl,
            'type' => $this->type,
        ];
    }
}
