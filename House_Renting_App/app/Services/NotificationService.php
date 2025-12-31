<?php

namespace App\Services;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function index($request){
 $user = $request->user();

        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);
            return $notifications;

    }
}
