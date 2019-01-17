<?php

namespace krivobokruslan\fayechat\server\messages;

abstract class BaseMessage
{

    private $eventName;

    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public abstract function convertToArray();

    public abstract function convertToJson();
}