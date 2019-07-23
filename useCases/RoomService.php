<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\entities\Room;
use krivobokruslan\fayechat\entities\RoomRole;
use krivobokruslan\fayechat\forms\RoomForm;
use krivobokruslan\fayechat\helpers\TransactionManager;
use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\repositories\RoomRepository;
use krivobokruslan\fayechat\repositories\RoomRoleRepository;
use krivobokruslan\fayechat\converted\Room as cRoom;

class RoomService
{
    private $rooms;
    private $transactions;
    private $roles;
    private $socketService;

    public function __construct(
        RoomRepository $rooms,
        RoomRoleRepository $roles,
        TransactionManager $transactions,
        SocketServiceInterface $socketService
    )
    {
        $this->rooms = $rooms;
        $this->transactions = $transactions;
        $this->roles = $roles;
        $this->socketService = $socketService;
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
        foreach ($room->members as $member) {
            if ($room->isOwner($member->id)) {
                continue;
            }
            $this->socketService->send('', ['event' => 'addRoom', 'room' => new cRoom($room), 'userId' => $member->id]);
        }

        return $room;
    }

//    public function view($id, $userId): RoomAdvanced
//    {
//        $room = $this->rooms->getById($id);
//        $messages = $this->messages->getByRoomForUser($room->id, $userId);
//        $roomAdvanced = new RoomAdvanced($room, $messages);
//        return $roomAdvanced;
//    }
//
//    public function index($userId, $appIdentifier): array
//    {
//        $user = $this->users->get($userId);
//        return $this->rooms->getByApplicationForUser($appIdentifier, $user->user_id);
//    }
//
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
//
//    public function leave($slug, $userId): void
//    {
//        $room = $this->rooms->getBySlug($slug);
//        if ($room->isOwner($userId)) {
//            // TODO it's need to set new owner from the moderators when the owner leave
//            return;
//        }
//        $room->detachMember($userId);
//        $this->rooms->save($room);
//    }
//
//    public function dismiss(RoomMembersForm $form, $roomId, $userId)
//    {
//        $room = $this->rooms->getById($roomId);
//        $room->checkPermissionMember($userId, RoomRole::MEMBER_DISMISS);
//        foreach ($form->members as $member) {
//            $room->detachMember($member);
//        }
//        $this->rooms->save($room);
//    }
//
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
}