<?php

namespace krivobokruslan\fayechat\components\role_manager;

use krivobokruslan\fayechat\entities\RoomMemberRole;
use krivobokruslan\fayechat\entities\RoomRole;

class RoleRepository
{
    public function saveRoleAssignment(RoomMemberRole $assignment): void
    {
        if (!$assignment->save()) {
            throw new \DomainException('Error while saving role assignment');
        }
    }

    public function deleteRoleAssignment(RoomMemberRole $assignment): void
    {
        if (!$assignment->delete()) {
            throw new \DomainException('Error while deleting role assignment');
        }
    }

    public function findRoleAssignment($roomId, $userId, $roleId): RoomMemberRole
    {
        return RoomMemberRole::findOne(['room_id' => $roomId, 'member_id' => $userId, 'role_id' => $roleId]);
    }

    public function findRolesAssignmentByUserInRoom($roomId, $userId): array
    {
        return RoomMemberRole::find()->where(['room_id' => $roomId, 'member_id' => $userId])->all();
    }

    public function findRoleByUserInRoom($roomId, $userId): RoomRole
    {
        $assignment = RoomMemberRole::find()->andWhere([ 'room_id' => $roomId])->andWhere([ 'member_id' => $userId])->one();
        return $assignment->role;

    }
}