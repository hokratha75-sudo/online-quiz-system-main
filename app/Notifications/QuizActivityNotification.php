<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QuizActivityNotification extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data; // [type, title, message, icon, url]
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // 'broadcast' requires Reverb/Pusher server — use 'database' only.
        // Notifications are still shown via the topbar bell icon (DB polling).
        return ['database'];
    }

    // toBroadcast() removed — broadcast channel disabled (Reverb not running).

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->data['type'] ?? 'info',
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'icon' => $this->data['icon'] ?? 'fas fa-bell',
            'url' => $this->data['url'] ?? '#'
        ];
    }
}
