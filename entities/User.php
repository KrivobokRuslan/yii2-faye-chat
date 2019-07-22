<?php

namespace krivobokruslan\fayechat\entities;

use krivobokruslan\fayechat\interfaces\UserInterface;
use krivobokruslan\fayechat\queries\UserQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property string $id
 * @property string $username
 * @property string $avatar
 * @property int $status
 *
 * @property RoomMemberRole[] $roomsAssignments
 * @property Room[] $rooms
 */

class User extends ActiveRecord implements UserInterface
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return '{{%chat_users}}';
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'username' => 'username',
            'avatar' => 'avatar'
        ];
    }

    public static function create(string $id, string $username, ?string $avatar): self
    {
        $user = new self();
        $user->id = $id;
        $user->username = $username;
        $user->avatar = $avatar;
        $user->status = self::STATUS_ACTIVE;
        return $user;
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function getChatUserId(): string
    {
        return $this->id;
    }

    public function getChatAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getChatUsername(): string
    {
        return $this->username;
    }

    public function getRoomsAssignments(): ActiveQuery
    {
        return $this->hasMany(RoomMemberRole::class, ['member_id' => 'user_id']);
    }

    public function getRooms(): ActiveQuery
    {
        return $this->hasMany(Room::class, ['id' => 'room_id'])->via('roomsAssignments');
    }
}