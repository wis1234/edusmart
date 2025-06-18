<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Events\NewNotification;
use Illuminate\Support\Collection;

class NotificationService
{
    public function send(User $user, string $type, string $title, string $message, ?string $link = null, ?array $data = null)
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data
        ]);

        event(new NewNotification($notification));

        return $notification;
    }

    public function sendToMany(Collection $users, string $type, string $title, string $message, ?string $link = null, ?array $data = null)
    {
        $notifications = [];
        foreach ($users as $user) {
            $notifications[] = $this->send($user, $type, $title, $message, $link, $data);
        }
        return collect($notifications);
    }

    public function sendToRole(string $role, string $type, string $title, string $message, ?string $link = null, ?array $data = null)
    {
        $users = User::role($role)->get();
        return $this->sendToMany($users, $type, $title, $message, $link, $data);
    }

    public function sendToAll(string $type, string $title, string $message, ?string $link = null, ?array $data = null)
    {
        $users = User::all();
        return $this->sendToMany($users, $type, $title, $message, $link, $data);
    }
} 