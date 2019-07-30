<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\components\role_manager\RoleManager;
use krivobokruslan\fayechat\entities\Room;
use krivobokruslan\fayechat\entities\RoomRole;
use krivobokruslan\fayechat\forms\RoomForm;
use krivobokruslan\fayechat\forms\RoomMembersForm;
use krivobokruslan\fayechat\helpers\TransactionManager;
use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\repositories\RoomMessageRepository;
use krivobokruslan\fayechat\repositories\RoomRepository;
use krivobokruslan\fayechat\repositories\RoomRoleRepository;
use krivobokruslan\fayechat\converted\Room as cRoom;
use krivobokruslan\fayechat\repositories\UserRepository;

class RoomService
{
    private $rooms;
    private $transactions;
    private $roles;
    private $socketService;
    private $messages;
    private $roleManager;
    private $users;

    public function __construct(
        RoomRepository $rooms,
        RoomRoleRepository $roles,
        TransactionManager $transactions,
        SocketServiceInterface $socketService,
        RoomMessageRepository $messages,
        RoleManager $roleManager,
        UserRepository $users
    )
    {
        $this->rooms = $rooms;
        $this->transactions = $transactions;
        $this->roles = $roles;
        $this->socketService = $socketService;
        $this->messages = $messages;
        $this->roleManager = $roleManager;
        $this->users = $users;
    }

    public function create(RoomForm $form, $ownerId): Room
    {
        $ownerRole = $this->roles->getRoleBySlug(RoomRole::ROLE_OWNER);
        $memberRole = $this->roles->getRoleBySlug(RoomRole::ROLE_MEMBER);
        $room = Room::create($form->title, $ownerId);
        foreach ($form->members as $member) {
            if ($member == $ownerId) {
                $room->attachMember($member, $ownerRole->id);
            } else {
                $room->attachMember($member, $memberRole->id);
            }
        }
        $this->rooms->save($room);
        $this->socketService->send('', ['event' => 'addRoom', 'room' => new cRoom($room, [], $form->members)]);
        return $room;
    }

    public function view($id, $userId): cRoom
    {
        $room = $this->rooms->getById($id);
        $messages = $this->messages->getByRoomForUser($room->id, $userId);
        $roomAdvanced = new cRoom($room, $messages, $room->members);
        return $roomAdvanced;
    }
//
//    public function index($userId, $appIdentifier): array
//    {
//        $user = $this->users->get($userId);
//        return $this->rooms->getByApplicationForUser($appIdentifier, $user->user_id);
//    }

//    public function addMembers(RoomMembersForm $form, $roomId, $userId): UserDataProvider
//    {
//        $memberRole = $this->roles->getRoleBySlug(RoomRole::ROLE_MEMBER);
//        $room = $this->rooms->getById($roomId);
//        if (!$room->isOwner($userId)){
//            $room->checkPermissionMember($userId, RoomRole::MEMBER_ADD);
//        }
//        foreach ($form->members as $member) {
//            $room->attachMember($member, $memberRole->id);
//        }
//        $this->transaction->wrap(function() use ($room, $form) {
//            $this->rooms->save($room);
//            foreach ($form->members as $member) {
//                \Yii::$app->faye->send(new RoomList($room), '/contacts/' . $member . '/add-to-room');
//            }
//            \Yii::$app->faye->send($this->users->getUsersByIds($form->members), '/room/' . $room->id . '/add-members');
//        });
//
//        return new UserDataProvider(['query' => User::find()->where(['in', 'user_id', $form->members])]);
//    }

    public function leave($id, $userId): void
    {
        $room = $this->rooms->getById($id);
        if ($room->isOwner($userId) && $room->getCountMembers() > 1) {
            throw new \DomainException('Вы не можете покинуть комнату пока в ней есть другие участники');
        }
        $room->detachMember($userId);
        $this->rooms->save($room);
        if ($room->getCountMembers() <= 0) {
            $room->delete();
            return;
        }
        foreach ($room->members as $member) {
            $this->socketService->send('', [
                'event' => 'leaveRoom',
                'roomId' => $room->id,
                'userId' => $member->id,
                'memberId' => $userId,
                'countMembers' => $room->getCountMembers()
            ]);
        }
    }

    public function delete($id, $userId): void
    {
        $room = $this->rooms->getById($id);
        $members = $room->members;
        if (!$room->isOwner($userId)) {
            throw new \DomainException('У вас недостаточно прав');
        }
        $room->delete();
        foreach ($members as $member) {
            $this->socketService->send('', [
                'event' => 'deleteRoom',
                'roomId' => $room->id
            ]);
        }
    }

    public function ban(RoomMembersForm $form, $roomId, $userId)
    {
        $room = $this->rooms->getById($roomId);
        if (!$room->isOwner($userId) && !$this->roleManager->canMembers($room->id, RoomRole::MEMBER_DISMISS, $userId)) {
            throw new \DomainException('У вас недостаточно прав');
        }
        foreach ($form->members as $member) {
            $room->detachMember($member);
        }
        $this->rooms->save($room);
        foreach ($form->members as $member) {
            $this->socketService->send('', ['event' => 'banRoomMember', 'roomId' => $room->id, 'userId' => $member]);
        }
        $this->socketService->send('', ['event' => 'changeRoomMemberCount', 'roomId' => $room->id, 'userId' => $userId, 'countMembers' => $room->getCountMembers()]);
    }

//    public function assignRole(RoomMemberRolesForm $form, $roomId, $userId): void
//    {
//        $room = $this->rooms->getById($roomId);
//        $room->guardIsOwner($userId);
//        if ($room->isOwner($form->member_id)){
//            throw new \DomainException('The user is the admin, the role will be not edit');
//        }
//        $role = $this->roles->getRoleBySlug($form->slug);
//        \Yii::$app->roleManager->assignRole($roomId, $form->member_id, $role->id);
//        \Yii::$app->faye->send(new RoomRoleAdvanced($role), '/room/'.$room->id.'/role/'.$form->member_id);
//    }

    public function usersList(): array
    {
        return $this->users->findAll();
    }
}