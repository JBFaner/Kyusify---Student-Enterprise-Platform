<?php

namespace App\Http\Controllers;

use App\Models\KyusifyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Return JSON list of recent notifications for the logged-in user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $notifications = KyusifyNotification::forUser($user->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['id', 'type', 'title', 'message', 'link', 'icon', 'is_read', 'created_at']);

        $unreadCount = KyusifyNotification::forUser($user->id)->unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Request $request, KyusifyNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    /**
     * Mark all notifications for this user as read.
     */
    public function markAllRead(Request $request)
    {
        KyusifyNotification::forUser(Auth::id())
            ->unread()
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
