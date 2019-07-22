<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\RoomMessageDeleted;
use krivobokruslan\fayechat\exceptions\NotFoundException;

class RoomMessageDeletedRepository
{
    public function save(RoomMessageDeleted $message): void
    {
        if (!$message->save()) {
            throw new \RuntimeException('Saving error');
        }
        $message->refresh();
    }

    public function getById($id): RoomMessageDeleted
    {
        return $this->getBy(['room_message_id' => $id]);
    }

    private function getBy(array $condition): RoomMessageDeleted
    {
        $query = RoomMessageDeleted::find()->andWhere($condition);
        /**
         * @var $message RoomMessageDeleted
         */
        if (!$message = $query->limit(1)->one()) {
            throw new NotFoundException('Message not found.');
        }
        return $message;
    }

    public function isExists($id, $userId)
    {
        return RoomMessageDeleted::findOne(['room_message_id' => $id, 'user_id' => $userId]);
    }
}