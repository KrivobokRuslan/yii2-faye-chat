<?php

namespace krivobokruslan\fayechat\server\messages;

class UserConnectMessage extends BaseMessage
{

    private $user_id;

    public function __construct($eventName, $user_id)
    {
        parent::__construct($eventName);
        $this->user_id = $user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public  function convertToArray()
    {
        return [
            'event' => $this->getEventName(),
            'user_id' => $this->getUserId()
        ];
    }

    public function convertToJson()
    {
        return json_encode($this->convertToArray());
    }

    public static function createAsArray(string $eventName, $user_id)
    {
        return (new self($eventName, $user_id))->convertToArray();
    }

    public static function createAsJson(string $eventName, $user_id)
    {
        return (new self($eventName, $user_id))->convertToJson();
    }
}