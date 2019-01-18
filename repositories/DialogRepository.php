<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\Dialog;
use krivobokruslan\fayechat\exceptions\NotFoundException;

class DialogRepository
{
    public function save(Dialog $dialog)
    {
        if (!$dialog->save()) {
            throw new \DomainException('Saving error');
        }
    }

    public function getById($id): Dialog
    {
        if (($dialog = Dialog::find()->byId($id)->one()) !== null) {
            return $dialog;
        }
        throw new NotFoundException();
    }

    public function findByUsers($currentUserId, $userId): ?Dialog
    {
        return Dialog::find()->where(['or', ['and', ['user_id_one' => $currentUserId], ['user_id_two' => $userId]], ['and', ['user_id_one' => $userId], ['user_id_two' => $currentUserId]]])->one();
    }
}