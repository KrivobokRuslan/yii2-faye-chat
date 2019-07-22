<?php

namespace krivobokruslan\fayechat\console\controllers;

use krivobokruslan\fayechat\entities\RoomRole;
use yii\console\Controller;

class RoleController extends Controller
{
    public function actionInitRoles()
    {
        if (!$ownerRole = RoomRole::findOne(['slug' => RoomRole::ROLE_OWNER])) {
            $ownerRole = RoomRole::create(RoomRole::ROLE_OWNER, RoomRole::MESSAGE_ALL, RoomRole::MEMBER_ALL);
            $ownerRole->save();
            echo 'Created role: Owner' . PHP_EOL;
        }
        if (!$memberRole = RoomRole::findOne(['slug' => RoomRole::ROLE_MEMBER])) {
            $memberRole = RoomRole::create(RoomRole::ROLE_MEMBER, RoomRole::MESSAGE_SEND);
            $memberRole->save();
            echo 'Created role: Member';
        }
    }
}