<?php

namespace krivobokruslan\fayechat\forms;

use yii\base\Model;

class DialogMessageForm extends Model
{
    public $message;

    public function rules()
    {
        return [
            ['message', 'required'],
            ['message', 'string']
        ];
    }
}