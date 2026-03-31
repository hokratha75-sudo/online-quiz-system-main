<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class QuizActivityNotification extends Notification implements ShouldBroadcast
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
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => $this->data['type'] ?? 'info',
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'icon' => $this->data['icon'] ?? 'fas fa-bell',
            'url' => $this->data['url'] ?? '#',
            'created_at' => now()->toISOString(),
        ]);
    }

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
