<?php

namespace krivobokruslan\fayechat\entities;

use krivobokruslan\fayechat\interfaces\UserInterface;
use krivobokruslan\fayechat\queries\UserQuery;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property string $id
 * @property string $username
 * @property string $avatar
 * @property int $status
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}