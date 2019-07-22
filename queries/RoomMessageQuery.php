<?php

namespace krivobokruslan\fayechat\queries;

use yii\db\ActiveQuery;
use krivobokruslan\fayechat\entities\RoomMessage;
use zichat\entities\RoomMessageDeleted;

class RoomMessageQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return RoomMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RoomMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function notDeleted()
    {
        $this->andWhere(['status' => RoomMessage::STATUS_NEW]);
        return $this;
    }

    public function notHided($userId)
    {
        $this->andWhere(['not exists', RoomMessageDeleted::find()->where('room_message_id = room_messages.id')->andWhere(['user_id' => $userId])]);
        return $this;
    }

    public function byRoom($roomId)
    {
        $this->andWhere(['room_id' => $roomId]);
        return $this;
    }
}