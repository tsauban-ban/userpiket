<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BroadcastNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $type;
    protected $sender;

    public function __construct($message, $type = 'info', $sender = 'Admin')
    {
        $this->message = $message;
        $this->type = $type;  // PASTIKAN INI ADA
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'sender' => $this->sender,
            'message' => $this->message,
            'type' => $this->type,  // PASTIKAN INI DIKIRIM
            'time' => now()->toDateTimeString()
        ];
    }
}