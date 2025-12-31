<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
  protected $notificationservice;
    public function __construct(NotificationService $notificationservice)
    {
        $this->notificationservice = $notificationservice;
    }
     public function index(Request $request)
    {
        $notifications =$this->notificationservice->index($request);
        return $this->success(NotificationResource::collection($notifications), 'Notifications retrieved successfully');
    }
     public function unreadCount(Request $request)
    {
        $count = $request->user()->unreadNotifications()->count();
        return $this->success(['unread_count' => $count], 'Unread count retrieved');
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return $this->success(true, 'Notification marked as read');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return $this->success(true, 'All notifications marked as read');
    }

}
