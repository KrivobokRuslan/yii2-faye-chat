<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\entities\DialogMessage;
use krivobokruslan\fayechat\forms\DialogMessageForm;
use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\repositories\DialogMessageRepository;
use krivobokruslan\fayechat\repositories\DialogRepository;

class DialogMessageService
{

    private $messages;
    private $socketService;
    private $dialogs;

    public function __construct(DialogMessageRepository $messages, SocketServiceInterface $socketService, DialogRepository $dialogs)
    {
        $this->messages = $messages;
        $this->socketService = $socketService;
        $this->dialogs = $dialogs;
    }

    public function create(DialogMessageForm $form, $dialogId, $currentUserId)
    {
        $dialog = $this->dialogs->getById($dialogId);
        $message = DialogMessage::create($currentUserId, $dialog->id, $form->message);
        $this->messages->save($message);
        $this->socketService->send('', ['event' => 'newMessage', 'userId' => $dialog->user_id_one == $currentUserId ? $dialog->user_id_two : $dialog->user_id_one, 'message' => $message->toArray()]);
        return $message;
    }
}