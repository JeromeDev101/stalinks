<?php

namespace App\Repositories\Contracts;

interface NotificationInterface
{
    public function newNotificationsCount($userId);

    public function updateUsersNotifications($data, $conditions = []);
}
