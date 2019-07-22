<?php

namespace krivobokruslan\fayechat\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "room_message_files".
 *
 * @property int $id
 * @property string $file
 * @property string $room_message_id
 * @property string $ctime
 *
 * @property RoomMessage $message
 */

class RoomMessageFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chat_room_message_files}}';
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

    public function fields()
    {
        return [
            'id' => 'id',
            'file' => 'file'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'file' => Yii::t('app', 'File'),
            'room_message_id' => Yii::t('app', 'Room Message ID'),
            'ctime' => Yii::t('app', 'Create time')
        ];
    }

    public static function create($url): self
    {
        $file = new self();
        $file->file = $url;
        return $file;
    }

    public function getMessage(): ActiveQuery
    {
        return$this->hasOne(RoomMessage::class, ['id' => 'room_message_id']);
    }
}