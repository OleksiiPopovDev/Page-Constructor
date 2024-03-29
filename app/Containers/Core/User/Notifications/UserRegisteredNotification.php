<?php

namespace App\Containers\Core\User\Notifications;

use App\Containers\Core\User\Models\User;
use App\Ship\Parents\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function toArray($notifiable): array
    {
        return [
            // ... do you own customization
        ];
    }
}
