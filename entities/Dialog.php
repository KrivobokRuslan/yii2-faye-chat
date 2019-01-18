<?php

namespace krivobokruslan\fayechat\entities;

use krivobokruslan\fayechat\queries\DialogQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Dialog model
 *
 * @property integer $id
 * @property integer $user_id_one
 * @property integer $user_id_two
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property DialogMessage[] $messages
 */

class Dialog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chat_dialogs}}';
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

    public static function create($user_id_one, $user_id_two)
    {
        $dialog = new self();
        $dialog->user_id_one = $user_id_one;
        $dialog->user_id_two = $user_id_two;
        return $dialog;
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(DialogMessage::class, ['dialog_id' => 'id']);
    }

    public static function find()
    {
        return new DialogQuery(get_called_class());
    }
}