<?php

namespace App\Repositories;

use App\Models\Notif;
use App\Repositories\Contracts\NotificationInterface;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository extends BaseRepository implements NotificationInterface
{
    protected $model;

    public function __construct(Notif $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    public function newNotificationsCount($userId)
    {
        return $this->findCount([
            'is_read' => 0,
            'user_id' => $userId
        ]);
    }

    public function updateUsersNotifications($data, $conditions = [])
    {
        return $this->model->where($conditions)->update($data);
    }
}
