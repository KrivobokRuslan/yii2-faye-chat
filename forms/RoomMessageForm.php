<?php

namespace krivobokruslan\fayechat\forms;

use yii\base\Model;
use krivobokruslan\fayechat\entities\Room;

class RoomMessageForm extends Model
{
    public $message;
    private $room;

    public function __construct(Room $room, array $config = [])
    {
        parent::__construct($config);
        $this->room = $room;
    }

    public function rules()
    {
        return [
            ['message', 'string'],
            ['message', 'required'],
            [['message'], 'filter', 'filter' => function ($value) {
                return strip_tags($value);
            }]
        ];
    }
}