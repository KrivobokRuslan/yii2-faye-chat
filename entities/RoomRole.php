<?php

namespace krivobokruslan\fayechat\entities;

use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "room_roles".
 *
 * @property int $id
 * @property string $name
 * @property int $message_permissions
 * @property int $member_permissions
 * @property int $status
 * @property string $slug
 * @property string $ctime
 *
 */

class RoomRole extends ActiveRecord
{

    const ROLE_OWNER = 'owner';
    const ROLE_MEMBER = 'member';

    const STATUS_ACTIVE = 10;
    const STATUS_NOT_ACTIVE = 20;

    const MESSAGE_SEND = 1 << 0;
    const MESSAGE_DELETE = 1 << 1;
    const MESSAGE_SEND_FILE = 1 << 2;

    const MESSAGE_ALL = self::MESSAGE_SEND | self::MESSAGE_DELETE | self::MESSAGE_SEND_FILE;

    const MEMBER_APPROVE = 1 << 0;
    const MEMBER_DISMISS = 1 << 1;
    const MEMBER_ADD = 1 << 2;
    const MEMBER_CONFIG_ROLE = 1 << 3;

    const MEMBER_ALL = self::MEMBER_APPROVE | self::MEMBER_DISMISS | self::MEMBER_ADD | self::MEMBER_CONFIG_ROLE;

    public static function tableName()
    {
        return '{{%chat_room_roles}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'ctime',
                'updatedAtAttribute' => false
            ]
        ];
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
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Role name'),
            'message_permissions' => Yii::t('app', 'Messaging permissions'),
            'member_permissions' => Yii::t('app', 'Members control permissions'),
            'status' => Yii::t('app', 'Status'),
            'ctime' => Yii::t('app', 'Created at'),
        ];
    }

    public static function create($name, $message_permissions = 0000, $member_permissions = 0000, $status = self::STATUS_ACTIVE): self
    {
        $role = new self();
        $role->name = $name;
        $role->message_permissions = $message_permissions;
        $role->member_permissions = $member_permissions;
        $role->status = $status;
        return $role;
    }

    public function edit($name, $message_permissions = 0000, $member_permissions = 0000, $status): void
    {
        $this->name = $name;
        $this->message_permissions = $message_permissions;
        $this->member_permissions = $member_permissions;
        $this->status = $status;
    }

    public function checkMessagePermission($permission): bool
    {
        return $this->message_permissions & $permission;
    }

    public function checkMemberPermission($permission): bool
    {
        return $this->member_permissions & $permission;
    }

    public function isUpdatable()
    {
        return $this->slug != self::ROLE_OWNER && $this->slug != self::ROLE_MEMBER;
    }
}