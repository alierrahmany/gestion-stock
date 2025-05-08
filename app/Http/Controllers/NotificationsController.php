<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display all notifications
     */
// app/Http/Controllers/NotificationsController.php
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

    /**
     * Mark a notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Admin can mark any notification as read
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

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        if (Auth::user()->role === 'admin') {
            Notification::where('read', false)->update(['read' => true]);
        } else {
            Auth::user()->notifications()
                  ->where('read', false)
                  ->update(['read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function unreadCount()
    {
        $count = Auth::user()->notifications()
                      ->unread()
                      ->count();

        return response()->json(['count' => $count]);
    }

    public function destroy(Notification $notification)
{
    // Authorization check
    if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
        abort(403);
    }

    $notification->delete();

    return response()->json(['success' => true]);
}
}
