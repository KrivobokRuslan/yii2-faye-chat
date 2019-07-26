<?php

namespace krivobokruslan\fayechat\entities;

use krivobokruslan\fayechat\exceptions\NotFoundException;
use krivobokruslan\fayechat\queries\RoomMessageQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * This is the model class for table "room_messages".
 *
 * @property int $id
 * @property string $message
 * @property int $room_id
 * @property int $author_user_id
 * @property int $status
 * @property string $ctime
 *
 * @property User $author
 * @property Room $room
 * @property RoomMessageFile[] $attachedFiles
 * @property RoomMessageDeleted $roomMessageDeleted
 */

class RoomMessage extends ActiveRecord
{

    const STATUS_NEW = 1;
    const STATUS_DELETED = 2;

    public static function tableName()
    {
        return '{{%chat_room_messages}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'ctime',
                'updatedAtAttribute' => false
            ],
            'saveRelations' => [
                'class' => SaveRelationsBehavior::className(),
                'relations' => [
                    'author',
                    'room',
                    'attachedFiles',
                    'roomMessageDeleted'
                ],
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function fields()
    {
        return [
            'id',
            'message',
            'attachedFiles',
            'room_id',
            'author_user_id',
            'status',
            'ctime'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
            'room_id' => Yii::t('app', 'Room ID'),
            'author_user_id' => Yii::t('app', 'Author ID'),
            'status' => Yii::t('app', 'Status'),
            'ctime' => Yii::t('app', 'Create time')
        ];
    }

    public static function create($text, $roomId, $authorId)
    {
        $message = new self();
        $message->message = $text;
        $message->room_id = $roomId;
        $message->author_user_id = $authorId;
        $message->status = self::STATUS_NEW;
        return $message;
    }

    public function removeForRoom()
    {
        if ($this->checkStatusDelete()) {
            $this->status = self::STATUS_DELETED;
        }
    }

    public function attachFile($url)
    {
        $files = $this->attachedFiles;
        if ($url) {
            $files[] = RoomMessageFile::create($url);
        }
        $this->attachedFiles = $files;
    }

    public function checkStatusDelete()
    {
        if ($this->status == self::STATUS_DELETED) {
            throw new \DomainException('Message already removed');
        }
        return true;
    }

    public function isAuthor($userId)
    {
        return $this->author_user_id == $userId;
    }

    public function checkOwner($userId)
    {
        if ($userId == $this->author_user_id) {
            return;
        }
        throw new NotFoundException('user are not the owner this is message');
    }

    public function getRoom(): ActiveQuery
    {
        return$this->hasOne(Room::class, ['id' => 'room_id']);
    }

    public function getAuthor(): ActiveQuery
    {
        return$this->hasOne(User::class, ['id' => 'author_user_id']);
    }

    public function getAttachedFiles(): ActiveQuery
    {
        return $this->hasMany(RoomMessageFile::class, ['room_message_id' => 'id']);
    }

    public function getRoomMessageDeleted(): ActiveQuery
    {
        return $this->hasMany(RoomMessageDeleted::class, ['room_message_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return RoomMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoomMessageQuery(get_called_class());
    }
}