<?php

namespace krivobokruslan\fayechat\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * DialogMessage model
 *
 * @property integer $id
 * @property integer $author_user_id
 * @property integer $dialog_id
 * @property string $message
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Dialog $dialog
 * @property User $author
 */

class DialogMessage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chat_dialog_messages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function create($authorId, $dialogId, $text): self
    {
        $message = new self();
        $message->author_user_id = $authorId;
        $message->dialog_id = $dialogId;
        $message->message = $text;
        return $message;
    }

    public function getDialog(): ActiveQuery
    {
        return $this->hasOne(Dialog::class, ['id' => 'dialog_id']);
    }

    public function isAuthor($userId): bool
    {
        return $this->author_user_id == $userId;
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_user_id']);
    }

    public function convertToArray()
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'author' => [
                'username' => $this->author->getChatUsername(),
                'avatar' => $this->author->getChatAvatar(),
                'id' => $this->author->getChatUserId()
            ],
            'dialog_id' => $this->dialog_id,
            'created_at' => date('Y-m-d H:i:s', $this->created_at)
        ];
    }
}