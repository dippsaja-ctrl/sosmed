<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/** Notifikasi sederhana di dalam aplikasi (lonceng di pojok kanan atas). */
class AppNotice extends Notification
{
    public function __construct(public string $type, public string $text) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return ['type' => $this->type, 'text' => $this->text];
    }
}
