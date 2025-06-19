<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        try {
            if ($notification->user_id === Auth::id()) {
                $notification->markAsRead();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            Auth::user()
                ->notifications()
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    public function markAllAsReadPost()
    {
        return $this->markAllAsRead();
    }

    public function getUnreadCount()
    {
        try {
            $count = Auth::user()
                ->notifications()
                ->whereNull('read_at')
                ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }
    }

    public function destroy(Notification $notification)
    {
        try {
            if ($notification->user_id === Auth::id()) {
                $notification->delete();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    public function show(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$notification->link) {
            return view('errors.notification', [
                'message' => 'This notification has no associated link.',
                'notification' => $notification
            ]);
        }

        return redirect($notification->link);
    }
} 