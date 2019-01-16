<?php

namespace krivobokruslan\fayechat\queries;

use krivobokruslan\fayechat\entities\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    public function notCurrentUser()
    {
        return $this->andWhere(['!=', 'id', \Yii::$app->user->id]);
    }
}