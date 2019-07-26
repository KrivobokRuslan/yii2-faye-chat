<?php

namespace krivobokruslan\fayechat\components\role_manager;

use yii\base\Component;
use krivobokruslan\fayechat\entities\RoomMemberRole;
use krivobokruslan\fayechat\helpers\TransactionManager;
use krivobokruslan\fayechat\components\role_manager\RoleRepository;

class RoleManager extends Component
{

    private $repository;
    private $transaction;

    public function __construct(RoleRepository $repository, TransactionManager $transaction, array $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function assignRole($roomId, $userId, $roleId): RoomMemberRole
    {
        $assignment = RoomMemberRole::create($userId, $roomId, $roleId);
        $this->transaction->wrap(function() use ($roomId, $userId, $assignment){
            $this->revokeRoles($roomId, $userId);
            $this->repository->saveRoleAssignment($assignment);
        });
        return $assignment;
    }

    public function revokeRoles($roomId, $userId): void
    {
        $assignments = $this->repository->findRolesAssignmentByUserInRoom($roomId, $userId);
        foreach ($assignments as $assignment) {
            $this->repository->deleteRoleAssignment($assignment);
        }
    }

    public function canMessaging($roomId, $permission, $userId)
    {
        $role = $this->getRoleForRoom($roomId, $userId);
        return $role->checkMessagePermission($permission);
    }

    public function canMembers($roomId, $permission, $userId)
    {
        $role = $this->getRoleForRoom($roomId, $userId);

        return $role->checkMemberPermission($permission);
    }

    public function getRoleForRoom($roomId, $userId)
    {
        return $this->repository->findRoleByUserInRoom($roomId, $userId);
    }
}