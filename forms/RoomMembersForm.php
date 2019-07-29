<?php

namespace krivobokruslan\fayechat\forms;

use yii\base\Model;
use krivobokruslan\fayechat\entities\User;

class RoomMembersForm extends Model
{
    public $members = [];

    public function rules()
    {
        return [
            [
                'members', 'each', 'rule' => [
                    'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'
                ]
            ],
        ];
    }
}