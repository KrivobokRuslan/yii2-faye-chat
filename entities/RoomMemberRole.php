<?php

namespace krivobokruslan\fayechat\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "room_member_roles".
 *
 * @property int $room_id
 * @property int $member_id
 * @property int $role_id
 *
 * @property Room $room
 * @property User $member
 * @property RoomRole $role
 *
 */

class RoomMemberRole extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chat_room_member_roles}}';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'room_id' => Yii::t('app', 'Room ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'role_id' => Yii::t('app', 'Role ID'),
        ];
    }

    public static function createByRoom($userId, $roleId): self
    {
        $assignment = new self();
        $assignment->member_id = $userId;
        $assignment->role_id = $roleId;
        return $assignment;
    }

    public static function create($userId, $roomId, $roleId): self
    {
        $assignment = new self();
        $assignment->member_id = $userId;
        $assignment->role_id = $roleId;
        $assignment->room_id = $roomId;
        return $assignment;
    }

    public function isMember($userId): bool
    {
        return $this->member_id == $userId;
    }

    public function getRole(): ActiveQuery
    {
        return $this->hasOne(RoomRole::class, ['id' => 'role_id']);
    }

    public function getRoom(): ActiveQuery
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    public function getMember(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'member_id']);
    }
}