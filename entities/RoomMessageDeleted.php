<?php

namespace krivobokruslan\fayechat\entities;

use yii\db\ActiveRecord;

/**
 *
 * @property string $room_message_id [integer]
 * @property string $user_id [integer]
 * @property string $deleted [integer]
 * @property RoomMessage roomMessage
 */

class RoomMessageDeleted extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%chat_room_message_deleted}}';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function remove($messageId, $userId)
    {
        $removeMessage = new self();
        $removeMessage->room_message_id = $messageId;
        $removeMessage->user_id = $userId;
        return $removeMessage;
    }

    public function getRoomMessage()
    {
        return $this->hasMany(RoomMessage::class, ['id' => 'room_message_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

}