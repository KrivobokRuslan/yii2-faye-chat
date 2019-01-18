<?php

namespace krivobokruslan\fayechat\queries;

use yii\db\ActiveQuery;

class DialogQuery extends ActiveQuery
{
    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }
}