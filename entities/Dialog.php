<?php

namespace krivobokruslan\fayechat\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Dialog model
 *
 * @property integer $id
 * @property integer $user_id_one
 * @property integer $user_id_two
 *
 * @property DialogMessage[] $messages
 */

class Dialog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chat_dialogs}}';
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
}