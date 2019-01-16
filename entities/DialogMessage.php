<?php

namespace krivobokruslan\fayechat\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * DialogMessage model
 *
 * @property integer $id
 * @property integer $author_user_id
 * @property integer $dialog_id
 * @property string $message
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
}