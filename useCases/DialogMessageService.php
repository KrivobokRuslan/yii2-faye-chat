<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\forms\DialogMessageForm;
use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\repositories\DialogMessageRepository;

class DialogMessageService
{

    private $messages;
    private $faye;

    public function __construct(DialogMessageRepository $messages, SocketServiceInterface $fayeService)
    {
        $this->messages = $messages;
        $this->faye = $fayeService;
    }

    public function create(DialogMessageForm $form)
    {

    }
}