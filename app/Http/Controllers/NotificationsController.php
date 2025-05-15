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
        // Authorization check
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read' => true]);

        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->role === 'admin'
                ? Notification::where('read', false)->count()
                : Auth::user()->notifications()->unread()->count()
        ]);
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
