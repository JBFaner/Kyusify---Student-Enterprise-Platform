<?php

namespace App\Helpers;

use App\Models\KyusifyNotification;

class NotificationHelper
{
    /**
     * Send a notification to a specific user.
     */
    public static function send(int $userId, string $type, string $title, string $message, ?string $link = null, string $icon = 'bell'): void
    {
        KyusifyNotification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'icon'    => $icon,
            'is_read' => false,
        ]);
    }

    /**
     * Notify all admin users.
     */
    public static function notifyAdmins(string $type, string $title, string $message, ?string $link = null, string $icon = 'bell'): void
    {
        $admins = \App\Models\User::where('role', 'admin')->pluck('id')->toArray();
        
        foreach ($admins as $adminId) {
            static::send($adminId, $type, $title, $message, $link, $icon);
        }
    }
}
