<?php

namespace krivobokruslan\fayechat\forms;

use yii\base\Model;

class RoomMessageForm extends Model
{
    public $message;

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