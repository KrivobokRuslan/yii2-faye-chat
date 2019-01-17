<?php

namespace krivobokruslan\fayechat\queries;

use krivobokruslan\fayechat\entities\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    public function active(): ActiveQuery
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    public function byId($id): ActiveQuery
    {
        return $this->andWhere(['id' => $id]);
    }

    public function notCurrentUser(): ActiveQuery
    {
        return $this->andWhere(['!=', 'id', \Yii::$app->user->id]);
    }

    public function current(): ActiveQuery
    {
        return $this->active()->andFilterWhere(['id' => \Yii::$app->user->id]);
    }
}