<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\DialogMessage;

class DialogMessageRepository
{
    public function save(DialogMessage $message): void
    {
        if (!$message->save()) {
            throw new \DomainException('Error while saving');
        }
    }
}