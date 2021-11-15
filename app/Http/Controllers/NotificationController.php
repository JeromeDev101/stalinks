<?php

namespace App\Http\Controllers;

use App\Models\Notif;
use App\Models\User;
use App\Repositories\Contracts\NotificationInterface;
use Illuminate\Database\Eloquent\Builder;
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

        $notifications = Notif::whereHasMorph(
            'notifiable',
            'App\Models\User',
        function (Builder $query) use ($user) {
            $query->where('id', $user->id);
        })->orderBy('created_at', 'DESC')
        ->take(10)
        ->get();

        return response()->json([
//            'data' => $user->notifications,
            'data' => $notifications,
            'new_notifications' => $user->unreadNotifications->count(),
            'all_notifications' => $user->notifications->count(),
        ]);
    }

    public function setUserNotificationsSeen($userId)
    {
        User::find($userId)->notifications->markAsRead();

        return 'OK';
    }

    public function getAllUserNotifications($userId)
    {
        $user = User::find($userId);

        $all_notifications = Notif::whereHasMorph(
            'notifiable',
            'App\Models\User',
            function (Builder $query) use ($user) {
                $query->where('id', $user->id);
            })->orderBy('created_at', 'DESC')
            ->paginate(10);

        return response()->json([
            'data' => $all_notifications,
        ]);
    }
}
