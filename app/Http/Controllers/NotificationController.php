<?php

namespace App\Http\Controllers;

use App\Models\Notif;
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
        return response()->json([
            'data' => $this->notification->find([
                'user_id' => $userId
            ])->take(5),
            'new_notifications' => $this->notification->newNotificationsCount($userId),
        ]);
    }

    public function setUserNotificationsSeen($userId)
    {
        $updateRes = $this->notification->updateUsersNotifications([
            'is_read' => 1
        ], [
            'user_id' => $userId
        ]);

        return response()->json($updateRes);
    }
}
