<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\RoomMemberRole;
use krivobokruslan\fayechat\entities\RoomRole;
use krivobokruslan\fayechat\exceptions\NotFoundException;

class RoomRoleRepository
{

    private $roomMemberRoleTable;
    private $table;

    public function __construct()
    {
        $this->roomMemberRoleTable = RoomMemberRole::tableName();
        $this->table = RoomRole::tableName();
    }

    public function save(RoomRole $role): void
    {
        if (!$role->save()){
            throw new \DomainException('Error while saving role');
        }
    }

    public function remove(RoomRole $role): void
    {
        if (!$role->delete()){
            throw new \DomainException('Error while deleting role');
        }
    }

    public function getById($id): RoomRole
    {
        return $this->get(['id' => $id]);
    }

    private function get($condition): RoomRole
    {
        if (!$role = RoomRole::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Room Role not found.');
        }
        return $role;
    }

    public function getRoles(): array
    {
        return RoomRole::find()->where(['!=', 'slug', RoomRole::ROLE_OWNER])->all();
    }

    public function getRoleBySlug($slug): RoomRole
    {
        return $this->get(['slug' => $slug]);
    }

    public function getByRoomAndUser($roomId, $userId): RoomRole
    {
        /**
         * @var RoomRole $role
         */
        $role = RoomRole::find()->leftJoin($this->roomMemberRoleTable, $this->roomMemberRoleTable . '.role_id = ' . $this->table . '.id')->where(['member_id' => $userId, 'room_id' => $roomId])->one();
        if ($role !== null) {
            return $role;
        }
        throw new NotFoundException('Role not found.');
    }
}