<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())->latest();

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search}%")
                    ->orWhere('message', 'LIKE', "%{$request->search}%")
                    ->orWhere('type', 'LIKE', "%{$request->search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Read status filter
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(8);

        $unreadCount = Notification::where('user_id', auth()->id())->unread()->count();

        $stats = [
            'total' => Notification::where('user_id', auth()->id())->count(),
            'unread' => $unreadCount,
            'read' => Notification::where('user_id', auth()->id())->read()->count(),
            'orders' => Notification::where('user_id', auth()->id())->where('type', 'order')->count(),
            'products' => Notification::where('user_id', auth()->id())->where('type', 'product')->count(),
            'system' => Notification::where('user_id', auth()->id())->where('type', 'system')->count(),
            'flash_sales' => Notification::where('user_id', auth()->id())->where('type', 'flash_sale')->count(),
        ];

        return view('notifications.index', compact('notifications', 'unreadCount', 'stats'));
    }

    public function getUnread()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        $count = $notifications->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca');
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus');
    }
}