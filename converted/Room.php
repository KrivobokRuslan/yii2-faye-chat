<?php

namespace krivobokruslan\fayechat\converted;

use krivobokruslan\fayechat\entities\Room as pRoom;

class Room
{

    private $room;

    public $id;
    public $title;
    public $countMembers;
    public $messages;
    public $members;

    public function __construct(pRoom $room, $messages = [], $members = [])
    {
        $this->room = $room;
        $this->id = $room->id;
        $this->title = $room->title;
        $this->countMembers = $room->getCountMembers();
        $this->messages = $messages;
        $this->members = $members;
    }
}