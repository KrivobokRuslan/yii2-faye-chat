<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\exceptions\NotFoundException;
use krivobokruslan\fayechat\entities\RoomMessage;

class RoomMessageRepository
{
    public function save(RoomMessage $message): void
    {
        if (!$message->save()) {
            throw new \RuntimeException('Saving error');
        }
        $message->refresh();
    }

    public function remove(RoomMessage $message): void
    {
        if (!$message->delete()) {
            throw new \RuntimeException('Removing error');
        }
    }

    public function getById($id): RoomMessage
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByRoomForUser($roomId, $userId): array
    {
        return RoomMessage::find()->notDeleted()->notHided($userId)->byRoom($roomId)->with(['author', 'attachedFiles'])->orderBy('ctime ASC')->all();
    }

    private function getBy(array $condition): RoomMessage
    {
        $query = RoomMessage::find()->andWhere($condition);
        /**
         * @var $message RoomMessage
         */
        if (!$message = $query->limit(1)->one()) {
            throw new NotFoundException('Message not found.');
        }
        return $message;
    }
}