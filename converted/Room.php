<?php

namespace krivobokruslan\fayechat\converted;

use krivobokruslan\fayechat\entities\Room as pRoom;

class Room
{

    public $id;
    public $title;
    public $countMembers;

    public function __construct(pRoom $room)
    {
        $this->id = $room->id;
        $this->title = $room->title;
        $this->countMembers = $room->getCountMembers();
    }
}