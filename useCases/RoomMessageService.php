<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\components\role_manager\RoleManager;
use krivobokruslan\fayechat\entities\RoomMessage;
use krivobokruslan\fayechat\entities\RoomMessageDeleted;
use krivobokruslan\fayechat\entities\RoomRole;
use krivobokruslan\fayechat\forms\RoomMessageForm;
use krivobokruslan\fayechat\helpers\TransactionManager;
use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\repositories\RoomMessageDeletedRepository;
use krivobokruslan\fayechat\repositories\RoomMessageRepository;
use krivobokruslan\fayechat\repositories\RoomRepository;
use yii\helpers\ArrayHelper;


class RoomMessageService
{

    private $messages;
    private $rooms;
    private $transaction;
    private $roleManager;
    private $deletedMessage;
    private $socketService;

    public function __construct(
        RoomMessageRepository $messages,
        RoomRepository $rooms,
        TransactionManager $transaction,
        RoomMessageDeletedRepository $deletedMessage,
        RoleManager $roleManager,
        SocketServiceInterface $socketService
    )
    {
        $this->messages = $messages;
        $this->rooms = $rooms;
        $this->transaction = $transaction;
        $this->deletedMessage = $deletedMessage;
        $this->roleManager = $roleManager;
        $this->socketService = $socketService;
    }

    public function create(RoomMessageForm $form, $roomId, $authorId): RoomMessage
    {
        $room = $this->rooms->getById($roomId);
        $room->guardIsMember($authorId);
        if (!$room->isOwner($authorId) && !$this->roleManager->canMessaging($room->id, RoomRole::MESSAGE_SEND, $authorId)) {
            throw new \DomainException('Access denied');
        }
        $message = RoomMessage::create($form->message, $room->id, $authorId);
        $this->messages->save($message);
        foreach ($room->members as $member) {
            if ($message->isAuthor($member->id)) {
                continue;
            }
            $this->socketService->send('', ['event' => 'newRoomMessage', 'message' => $message->convertToArray(), 'userId' => $member->id]);
        }
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