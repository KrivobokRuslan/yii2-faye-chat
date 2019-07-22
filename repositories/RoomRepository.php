<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\Room;
use krivobokruslan\fayechat\entities\RoomMemberRole;
use krivobokruslan\fayechat\exceptions\NotFoundException;

class RoomRepository
{

    private $roomMemberTable;
    private $table;

    public function __construct()
    {
        $this->roomMemberTable = RoomMemberRole::tableName();
        $this->table = Room::tableName();
    }

    public function save(Room $room): void
    {
        if (!$room->save()) {
            throw new \RuntimeException('Saving error');
        }
        $room->refresh();
    }

    public function remove(Room $room): void
    {
        if (!$room->delete()) {
            throw new \RuntimeException('Removing error');
        }
    }

    public function getById($id): Room
    {
        return $this->getBy(['id' => $id]);
    }

    public function getBySlug($slug): Room
    {
        return $this->getBy(['slug' => $slug]);
    }

    private function getBy(array $condition): Room
    {
        $query = Room::find()->andWhere($condition);
        /**
         * @var $room Room
         */
        if (!$room = $query->limit(1)->one()) {
            throw new NotFoundException('Room not found.');
        }
        return $room;
    }

    public function getByUser($userId): array
    {
        return Room::find()->leftJoin($this->roomMemberTable, $this->roomMemberTable . '.room_id = ' . $this->table . '.id')->where(['member_id' => $userId])->all();
    }
}