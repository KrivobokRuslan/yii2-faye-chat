<?php

namespace krivobokruslan\fayechat\forms;

use krivobokruslan\fayechat\entities\User;
use yii\base\Model;

class DialogCreateForm extends Model
{
    public $user_id;

    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['user_id', 'compare', 'compareValue' => \Yii::$app->user->id, 'operator' => '!=']
        ];
    }
}