<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\components\role_manager\RoleManager;
use krivobokruslan\fayechat\entities\RoomMessage;
use krivobokruslan\fayechat\entities\RoomMessageDeleted;
use krivobokruslan\fayechat\entities\RoomRole;
use krivobokruslan\fayechat\forms\RoomMessageForm;
use krivobokruslan\fayechat\helpers\TransactionManager;
use krivobokruslan\fayechat\repositories\RoomMessageDeletedRepository;
use krivobokruslan\fayechat\repositories\RoomMessageRepository;
use krivobokruslan\fayechat\repositories\RoomRepository;


class RoomMessageService
{

    private $messages;
    private $rooms;
    private $transaction;
    private $roleManager;
    private $deletedMessage;

    public function __construct(
        RoomMessageRepository $messages,
        RoomRepository $rooms,
        TransactionManager $transaction,
        RoomMessageDeletedRepository $deletedMessage,
        RoleManager $roleManager
    )
    {
        $this->messages = $messages;
        $this->rooms = $rooms;
        $this->transaction = $transaction;
        $this->deletedMessage = $deletedMessage;
        $this->roleManager = $roleManager;
    }

    public function create(RoomMessageForm $form, $roomId, $authorId): RoomMessage
    {
        $room = $this->rooms->getById($roomId);
        $room->guardIsMember($authorId);
        if (!$room->isOwner($authorId) && !$this->roleManager->canMembers($room->id, RoomRole::MESSAGE_SEND, $authorId)) {
            throw new \DomainException('Access denied');
        }
        $message = RoomMessage::create($form->message, $room->id, $authorId);
        $this->messages->save($message);
        return $message;
    }

    public function deleteMe($messageId, $userId): RoomMessage
    {
        $message = $this->messages->getById($messageId);
        $message->checkStatusDelete();
        $messageRemove = $this->deletedMessage->isExists($message->id, $userId);
        if (empty($messageRemove)) {
            $deleted = RoomMessageDeleted::remove($messageId, $userId);
            $this->deletedMessage->save($deleted);
        }
        return $message;
    }

    public function delete($messageId, $userId): RoomMessage
    {
        $message = $this->messages->getById($messageId);
        $room = $message->room;
        if (!$message->isAuthor($userId) && !$room->isOwner($userId)){
            throw new \DomainException('Access denied');
        }
        $message->removeForRoom();
        $this->messages->save($message);
        return $message;
    }
}