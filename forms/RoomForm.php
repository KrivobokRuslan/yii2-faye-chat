<?php

namespace krivobokruslan\fayechat\forms;

use yii\helpers\ArrayHelper;
use krivobokruslan\fayechat\entities\Room;
use yii\base\Model;
use krivobokruslan\fayechat\entities\User;

class RoomForm extends Model
{
    public $title;
    public $members = [];

    public function __construct(Room $room = null, array $config = [])
    {
        parent::__construct($config);
        if ($room) {
            $this->title = $room->title;
            $this->members = ArrayHelper::getColumn($room->membersAssignments, 'user_id');
        }
    }

    public function rules()
    {
        return [
            [['title', 'members'], 'required'],
            [['title'], 'string'],
            [['members'], 'each', 'rule' => [
                'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'
            ]
            ],
            [['title'], 'filter', 'filter' => function ($value) {
                return strip_tags($value);
            }]
        ];
    }

    public function addUserToMembers($userId)
    {
        if (!in_array($userId, $this->members)) {
            $this->members[] = $userId;
        }
    }
}