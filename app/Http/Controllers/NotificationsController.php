<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        // Base query
        $query = Auth::user()->role === 'admin'
            ? Notification::with('actionUser')->latest()
            : Auth::user()->notifications()->with('actionUser')->latest();

        // Apply filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'product':
                case 'sale':
                case 'purchase':
                    $query->where('type', $request->filter);
                    break;
                case 'unread':
                    $query->where('read', false);
                    break;
            }
        }

        $notifications = $query->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
{
    // Debugging: Log the incoming request
    \Log::info('MarkAsRead request:', [
        'notification_id' => $notification->id,
        'user_id' => auth()->id(),
        'notification_user_id' => $notification->user_id
    ]);

    // Authorization check
    if (auth()->user()->role !== 'admin' && $notification->user_id !== auth()->id()) {
        \Log::warning('Unauthorized attempt to mark notification as read', [
            'user_id' => auth()->id(),
            'notification_id' => $notification->id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized action'
        ], 403);
    }

    try {
        $updated = $notification->update(['read' => true]);

        if (!$updated) {
            throw new \Exception('Failed to update notification in database');
        }

        \Log::info('Notification marked as read successfully', [
            'notification_id' => $notification->id
        ]);

        return response()->json([
            'success' => true,
            'unread_count' => auth()->user()->role === 'admin'
                ? Notification::where('read', false)->count()
                : auth()->user()->notifications()->unread()->count()
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to mark notification as read: ' . $e->getMessage(), [
            'notification_id' => $notification->id,
            'error' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update notification: ' . $e->getMessage()
        ], 500);
    }
}

    public function markAllAsRead()
    {
        if (Auth::user()->role === 'admin') {
            Notification::where('read', false)->update(['read' => true]);
        } else {
            Auth::user()->notifications()
                ->where('read', false)
                ->update(['read' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function unreadCount()
    {
        $count = Auth::user()->role === 'admin'
            ? Notification::where('read', false)->count()
            : Auth::user()->notifications()->unread()->count();

        return response()->json(['count' => $count]);
    }

    public function destroy(Notification $notification)
    {
        try {
            // Authorization check
            if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Notification deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }
}
