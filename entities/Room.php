<?php

namespace krivobokruslan\fayechat\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property string $title
 * @property int $owner_user_id
 * @property string $ctime
 *
 * @property User $owner
 * @property RoomMemberRole[] $membersAssignments
 * @property User[] $members
 * @property RoomMessage[] $messages
 * @property RoomMemberRole[] $roomMemberRoles
 */

class Room extends ActiveRecord
{

    const TYPE_PUBLIC = 1;
    const TYPE_PRIVATE = 2;

    public static function tableName()
    {
        return '{{%chat_rooms}}';
    }

    public function behaviors()
    {
        return [
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
            'title' => Yii::t('app', 'Title'),
            'owner_user_id' => Yii::t('app', 'Owner'),
            'ctime' => Yii::t('app', 'Create time')
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'owner_user_id',
            'ctime'
        ];
    }

    public function extractFields()
    {
        return [
            'lastMessage' => 'lastMessage'
        ];
    }

    public static function create($title, $ownerId): self
    {
        $model = new self();
        $model->title = $title;
        $model->owner_user_id = $ownerId;
        return $model;
    }

    public function isOwner($userId): bool
    {
        return $this->owner_user_id == $userId;
    }

    public function isMember($userId): bool
    {
        return in_array($userId, ArrayHelper::getColumn($this->membersAssignments, 'member_id'));
    }

    public function guardIsMember($userId): void
    {
        if (!$this->isMember($userId)) {
            throw new \DomainException('Access denied');
        }
    }

    public function guardIsOwner($userId): void
    {
        if (!$this->isOwner($userId)) {
            throw new \DomainException('Access denied');
        }
    }

    public function assignRoleMember($memberId, $roomId, $roleId, $userId): void
    {
        if (!$this->isMember($memberId)) {
            throw new \DomainException('There is no such member in the group');
        }
        if (!$this->isOwner($userId)) {
            throw new \DomainException('Not owner');
        }
        $this->roomMemberRoles = RoomMemberRole::create($memberId, $roomId, $roleId);
    }

    public function attachMember($memberUserId, $roleId): void
    {
        $assignments = $this->membersAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isMember($memberUserId)) {
                throw new \DomainException('This user already exists in this room');
            }
        }
        $assignments[] = RoomMemberRole::createByRoom($memberUserId, $roleId);
        $this->membersAssignments = $assignments;
    }

    public function detachMember($memberUserId): void
    {
        $assignments = $this->membersAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isMember($memberUserId) && !$this->isOwner($memberUserId)) {
                unset($assignments[$i]);
                $this->membersAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Member is not found.');
    }

    public function checkPermissionMessage($userId, $permission)
    {
        if(!$this->isOwner($userId)){
            if (!\Yii::$app->roleManager->canMessaging($this->id, $permission, $userId)){
                throw new \DomainException('Access denied');
            }
        }
    }

    public function checkPermissionMember($userId, $permission)
    {

        if(!$this->isOwner($userId)){
            if (!\Yii::$app->roleManager->canMembers($this->id, $permission, $userId)){
                throw new \DomainException('Access denied');
            }
        }
    }

    public function getOwner(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'owner_user_id']);
    }

    public function getMembersAssignments(): ActiveQuery
    {
        return $this->hasMany(RoomMemberRole::class, ['room_id' => 'id']);
    }

    public function getMembers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['user_id' => 'member_id'])->via('membersAssignments');
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(RoomMessage::class, ['room_id' => 'id']);
    }

    public function getRoomMemberRoles(): ActiveQuery
    {
        return $this->hasOne(RoomMemberRole::class, ['room_id' => 'id']);
    }

    public function getLastMessage(): ActiveQuery
    {
        return $this->hasOne(RoomMessage::class, ['room_id' => 'id'])->orderBy('ctime DESC');
    }

    public function getCountMembers(): int
    {
        return $this->getMembers()->count();
    }
}