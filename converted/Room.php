<?php

namespace krivobokruslan\fayechat\converted;

use krivobokruslan\fayechat\entities\Room as pRoom;
use yii\helpers\ArrayHelper;

class Room
{

    private $room;

    public $id;
    public $title;
    public $owner_user_id;
    public $countMembers;
    public $messages;
    public $members;

    public function __construct(pRoom $room, $messages = [], $members = [])
    {
        $this->room = $room;
        $this->id = $room->id;
        $this->title = $room->title;
        $this->owner_user_id = $room->owner_user_id;
        $this->countMembers = $room->getCountMembers();
        $this->messages = $messages;
        $this->members = $members;
    }

    public function isOwner($userId): bool
    {
        return $this->owner_user_id == $userId;
    }
}