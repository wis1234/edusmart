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
        $user = Auth::user();
        if ($user->role === 'admin') {
            // Supreme admin: see all notifications
            $notifications = \App\Models\Notification::latest()->paginate(10);
        } elseif ($user->role === 'school_admin' && $user->school_id) {
            $userIds = \App\Models\User::where('school_id', $user->school_id)->pluck('id');
            $notifications = \App\Models\Notification::whereIn('user_id', $userIds)
                ->latest()
                ->paginate(10);
        } else {
            $notifications = $user->notifications()->latest()->paginate(10);
        }
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        try {
            $user = Auth::user();
            $canMark = false;
            if ($user->role === 'admin') {
                $canMark = true;
            } elseif ($user->role === 'school_admin' && $user->school_id) {
                $canMark = \App\Models\User::where('id', $notification->user_id)->where('school_id', $user->school_id)->exists();
            } elseif ($notification->user_id === $user->id) {
                $canMark = true;
            }
            if ($canMark) {
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
            $user = Auth::user();
            if ($user->role === 'admin') {
                // Mark all notifications in the system as read
                \App\Models\Notification::whereNull('read_at')->update(['read_at' => now()]);
            } elseif ($user->role === 'school_admin' && $user->school_id) {
                // Mark all notifications of users in the school as read
                $userIds = \App\Models\User::where('school_id', $user->school_id)->pluck('id');
                \App\Models\Notification::whereIn('user_id', $userIds)->whereNull('read_at')->update(['read_at' => now()]);
            } else {
                // Default: only own notifications
                $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
            }
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
            $user = Auth::user();
            $canDelete = false;
            if ($user->role === 'admin') {
                $canDelete = true;
            } elseif ($user->role === 'school_admin' && $user->school_id) {
                $canDelete = \App\Models\User::where('id', $notification->user_id)->where('school_id', $user->school_id)->exists();
            } elseif ($notification->user_id === $user->id) {
                $canDelete = true;
            }
            if ($canDelete) {
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