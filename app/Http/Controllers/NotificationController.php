<?php

namespace App\Http\Controllers;

use App\Models\Notif;
use App\Models\User;
use App\Repositories\Contracts\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notification;

    public function __construct(NotificationInterface $notificationInterface)
    {
        $this->notification = $notificationInterface;
    }

    /**
     * Get user's notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotifications($userId)
    {
        $user = User::find($userId);

        return response()->json([
            'data' => $user->notifications,
            'new_notifications' => $user->unreadNotifications->count()
        ]);
    }

    public function setUserNotificationsSeen($userId)
    {
        User::find($userId)->notifications->markAsRead();

        return 'OK';
    }
}
