<?php

namespace krivobokruslan\fayechat\server\messages;

class UsersOnlineMessage extends BaseMessage
{

    private $users;

    public function __construct($eventName, array $users)
    {
        parent::__construct($eventName);
        $this->users = $users;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public  function convertToArray()
    {
        return [
            'event' => $this->getEventName(),
            'users' => $this->getUsers()
        ];
    }

    public function convertToJson()
    {
        return json_encode($this->convertToArray());
    }

    public static function createAsArray(string $eventName, array $users)
    {
        return (new self($eventName, $users))->convertToArray();
    }

    public static function createAsJson(string $eventName, array $users)
    {
        return (new self($eventName, $users))->convertToJson();
    }
}